<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use App\Models\Note;
use App\Models\Presence;
use App\Models\Demande;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        // Moyenne générale
        $moyenne = Note::where('etudiant_id', $etudiant->id)->avg('note_finale') ?? 0;
        
        // Absences (sans justifiee)
        $totalAbsences = Presence::where('etudiant_id', $etudiant->id)
            ->where('statut', 'Absent')->count();
        $absencesNonJustifiees = $totalAbsences; // Temporaire
        
        // Modules
        $modules = $etudiant->inscriptions()->with('module')->get();
        $demandesEnAttente = Demande::where('etudiant_id', $etudiant->id)
            ->where('statut', 'En attente')->count();
        
        // Cours aujourd'hui
        $coursAujourdhui = EmploiDuTemps::where('jour', $this->jourFrancais(now()->dayOfWeek))
            ->whereHas('module', function($q) use ($etudiant) {
                $q->whereIn('id', $etudiant->inscriptions->pluck('module_id'));
            })->with(['module', 'salle', 'professeur.user'])->get();
        
        return view('etudiant.dashboard', compact('moyenne', 'totalAbsences', 'absencesNonJustifiees', 
            'modules', 'demandesEnAttente', 'coursAujourdhui'));
    }
    
    private function jourFrancais(int $day): string {
        return match($day) { 1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi', default=>'Lundi' };
    }
}