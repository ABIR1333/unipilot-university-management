<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Note, Presence, Etudiant, Module, Programme};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class RapportController extends Controller {
    public function index(Request $request) {
        $semestre = $request->semestre ?? 4;
        $annee    = $request->annee ?? '2024-2025';

        // Performance académique par mois
        $performanceMois = Note::whereNotNull('note_finale')
            ->where('semestre',$semestre)->where('annee_academique',$annee)
            ->selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, AVG(note_finale) as moyenne')
            ->groupBy('annee','mois')->orderBy('annee')->orderBy('mois')->get();

        // Taux de présence par mois
        $presenceMois = Presence::where('created_at','>=',now()->subMonths(7))
            ->selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, SUM(statut="Présent") as presents, COUNT(*) as total')
            ->groupBy('annee','mois')->orderBy('annee')->orderBy('mois')->get()
            ->map(fn($p) => array_merge($p->toArray(),['taux'=>$p->total>0?round($p->presents/$p->total*100):0]));

        // Moyennes par module
      $moyennesModules = Module::with('programme')
    ->withAvg(
        ['notes as moy'=>fn($q)=>$q->where('semestre',$semestre)
                                   ->whereNotNull('note_finale')],
        'note_finale'
    )
    ->where('is_active', true)
    ->get();

        // Distribution mentions
        $distributionMentions = Note::whereNotNull('mention')
            ->where('semestre',$semestre)->where('annee_academique',$annee)
            ->selectRaw('mention, COUNT(*) as count')->groupBy('mention')->get();

        $semestres = [1,2,3,4,5,6];
        return view('admin.rapports.index', compact(
            'performanceMois','presenceMois','moyennesModules','distributionMentions','semestre','annee','semestres'
        ));
    }

    public function exportPdf(Request $request) {
        $semestre = $request->semestre ?? 4;
        $annee    = $request->annee ?? '2024-2025';

        $moyennesModules = Module::with('programme')
    ->withAvg(
        ['notes as moy'=>fn($q)=>$q->where('semestre',$semestre)
                                   ->whereNotNull('note_finale')],
        'note_finale'
    )
    ->where('is_active', true)
    ->get();

        $distributionMentions = Note::whereNotNull('mention')
            ->where('semestre',$semestre)->where('annee_academique',$annee)
            ->selectRaw('mention, COUNT(*) as count')->groupBy('mention')->get();

        $statsGenerales = [
            'total_etudiants' => Etudiant::where('statut','Actif')->count(),
            'moyenne_generale'=> round(Note::where('semestre',$semestre)->whereNotNull('note_finale')->avg('note_finale')??0,1),
            'taux_reussite'   => $this->calcTauxReussite($semestre,$annee),
            'taux_presence'   => $this->calcTauxPresence(),
        ];

$pdf = Pdf::loadView('pdf.rapport', compact('moyennesModules','distributionMentions','statsGenerales','semestre','annee'));
$pdf->setPaper('A4','portrait');
return $pdf->download("rapport_S{$semestre}_{$annee}.pdf");
    }

    private function calcTauxReussite($semestre,$annee): float {
        $total = Note::where('semestre',$semestre)->where('annee_academique',$annee)->whereNotNull('note_finale')->count();
        $reussis = Note::where('semestre',$semestre)->where('annee_academique',$annee)->where('note_finale','>=',10)->count();
        return $total > 0 ? round($reussis/$total*100,1) : 0;
    }

    private function calcTauxPresence(): float {
        $total = Presence::count();
        $pres  = Presence::where('statut','Présent')->count();
        return $total > 0 ? round($pres/$total*100,1) : 0;
    }
}
