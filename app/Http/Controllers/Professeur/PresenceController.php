<?php
namespace App\Http\Controllers\Professeur;
use App\Http\Controllers\Controller;
use App\Models\{Presence, Module, Etudiant, EmploiDuTemps};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller {
    public function index(Request $request) {
        $professeur = Auth::user()->professeur;
        $modules    = $professeur->modules()->with('programme')->get();

        // Build seances list (emploi du temps entries for this prof)
        $seances = EmploiDuTemps::where('professeur_id', $professeur->id)
            ->with(['module','salle'])
            ->orderBy('jour')->orderBy('heure_debut')
            ->get()
            ->map(fn($s) => [
                'value' => $s->id,
                'label' => "{$s->module->nom} — {$s->type_seance} {$s->jour} ".
                           \Carbon\Carbon::parse($s->heure_debut)->format('H:i'),
                'module_id' => $s->module_id,
            ]);

        $selectedSeanceId = $request->seance_id ?? $seances->first()['value'] ?? null;
        $selectedSeance   = $selectedSeanceId
            ? EmploiDuTemps::with(['module','salle'])->find($selectedSeanceId)
            : null;

        $date = $request->date ?? today()->toDateString();

        $presences   = collect();
        $statsToday  = ['presents' => 0, 'absents' => 0, 'taux' => 0];

        if ($selectedSeance) {
            $etudiants = Etudiant::whereHas('inscriptions', fn($q) =>
                $q->where('module_id', $selectedSeance->module_id)
            )->with('user')->get();

            $presencesExistantes = Presence::where('module_id', $selectedSeance->module_id)
                ->where('date', $date)
                ->get()->keyBy('etudiant_id');

            $presences = $etudiants->map(fn($e) => [
                'etudiant' => $e,
                'statut'   => $presencesExistantes[$e->id]->statut ?? 'Présent',
            ]);

            $presents = $presences->where('statut', 'Présent')->count();
            $absents  = $presences->where('statut', 'Absent')->count();
            $total    = $presences->count();
            $statsToday = [
                'presents' => $presents,
                'absents'  => $absents,
                'taux'     => $total > 0 ? round($presents / $total * 100) : 0,
            ];
        }

        return view('professeur.presences', compact(
            'modules','seances','selectedSeanceId','selectedSeance','date','presences','statsToday'
        ));
    }

    public function store(Request $request) {
        $professeur = Auth::user()->professeur;

        $v = $request->validate([
            'module_id'   => 'required|exists:modules,id',
            'date'        => 'required|date',
            'presences'   => 'required|array',
            'presences.*.etudiant_id' => 'required|exists:etudiants,id',
            'presences.*.statut'      => 'required|in:Présent,Absent,Justifié',
        ]);

        // Verify module belongs to this professor
        if (!$professeur->modules->pluck('id')->contains($v['module_id'])) abort(403);

        foreach ($v['presences'] as $p) {
            Presence::updateOrCreate(
                ['etudiant_id' => $p['etudiant_id'], 'module_id' => $v['module_id'], 'date' => $v['date']],
                ['statut' => $p['statut']]
            );
        }

        return redirect()->back()->with('success', 'Feuille de présence validée.');
    }

    public function tousPresentsBulk(Request $request) {
        $professeur = Auth::user()->professeur;
        $v = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'date'      => 'required|date',
        ]);

        $etudiants = Etudiant::whereHas('inscriptions', fn($q) =>
            $q->where('module_id', $v['module_id'])
        )->get();

        foreach ($etudiants as $e) {
            Presence::updateOrCreate(
                ['etudiant_id' => $e->id, 'module_id' => $v['module_id'], 'date' => $v['date']],
                ['statut' => 'Présent']
            );
        }

        return redirect()->back()->with('success', 'Tous les étudiants marqués présents.');
    }
}
