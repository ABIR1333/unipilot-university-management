<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{EmploiDuTemps, Module, Professeur, Salle};
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller {
    public function index(Request $request) {
        $annee = $request->annee ?? '2024-2025';
        $semestre = $request->semestre ?? 4;
        $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];

        $emplois = EmploiDuTemps::with(['module.programme','professeur.user','salle'])
            ->where('annee_academique',$annee)
            ->where('semestre',$semestre)
            ->get()
            ->groupBy('jour');

        $modules     = Module::with('programme')->where('is_active',true)->get();
        $professeurs = Professeur::with('user')->where('statut','Actif')->get();
        $salles      = Salle::all();

        return view('admin.emploi-du-temps.index', compact('emplois','jours','annee','semestre','modules','professeurs','salles'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'module_id'       => 'required|exists:modules,id',
            'professeur_id'   => 'required|exists:professeurs,id',
            'salle_id'        => 'nullable|exists:salles,id',
            'jour'            => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi',
            'heure_debut'     => 'required',
            'heure_fin'       => 'required',
            'type_seance'     => 'required|in:CM,TD,TP',
            'annee_academique'=> 'required|string',
            'semestre'        => 'required|integer',
        ]);
        EmploiDuTemps::create($v);
        return redirect()->route('admin.emploi-du-temps.index')->with('success','Séance ajoutée.');
    }

    public function destroy(EmploiDuTemps $emploiDuTemps) {
        $emploiDuTemps->delete();
        return redirect()->route('admin.emploi-du-temps.index')->with('success','Séance supprimée.');
    }

    public function exportPdf(Request $request) {
        $annee    = $request->annee ?? '2024-2025';
        $semestre = $request->semestre ?? 4;
        $jours    = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];
        $emplois  = EmploiDuTemps::with(['module','professeur.user','salle'])
            ->where('annee_academique',$annee)->where('semestre',$semestre)
            ->get()->groupBy('jour');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.emploi-du-temps', compact('emplois','jours','annee','semestre'));
        $pdf->setPaper('A4','landscape');
        return $pdf->download("emploi_du_temps_S{$semestre}.pdf");
    }
}
