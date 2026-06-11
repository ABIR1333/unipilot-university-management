<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\{EmploiDuTemps, Note, Inscription};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Vérification que l'utilisateur a un profil professeur
        $professeur = Auth::user()->professeur;
        
        if (!$professeur) {
            abort(403);
        }

        // Récupérer les modules du professeur
        $modules = $professeur->modules()->with('programme')->get();

        // Données pour la vue
        $data = [
            'professeur' => $professeur,
            'modules' => $modules,
            'coursAujourdhui' => $this->getCoursAujourdhui($professeur),
            'emploiSemaine' => $this->getEmploiSemaine($professeur),
            'jours' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
            'notesASaisir' => $this->getNotesASaisir($modules),
            'totalEtudiants' => $this->getTotalEtudiants($modules),
            'aFaire' => $this->getTaches($modules),
        ];

        return view('professeur.dashboard', $data);
    }

    private function getCoursAujourdhui($professeur)
    {
        return EmploiDuTemps::where('professeur_id', $professeur->id)
            ->where('jour', $this->jourFrancais(now()->dayOfWeek))
            ->with(['module', 'salle'])
            ->orderBy('heure_debut')
            ->get();
    }

    private function getEmploiSemaine($professeur)
    {
        return EmploiDuTemps::where('professeur_id', $professeur->id)
            ->with(['module', 'salle'])
            ->get()
            ->groupBy('jour');
    }

    private function getNotesASaisir($modules)
    {
        return Note::whereIn('module_id', $modules->pluck('id'))
            ->whereNull('note_examen')
            ->count();
    }

    private function getTotalEtudiants($modules)
    {
        return Inscription::whereIn('module_id', $modules->pluck('id'))
            ->distinct('etudiant_id')
            ->count('etudiant_id');
    }

    private function getTaches($modules)
    {
        $notesASaisir = $this->getNotesASaisir($modules);
        
        return [
            ['texte' => "Saisir notes examen ({$notesASaisir} étudiants)", 'priorite' => $notesASaisir > 0 ? 'urgent' : 'normal'],
            ['texte' => 'Publier corrigé TD n°5', 'priorite' => 'normal'],
            ['texte' => 'Préparer cours suivant', 'priorite' => 'normal'],
        ];
    }

    private function jourFrancais(int $dayOfWeek): string
    {
        return match($dayOfWeek) {
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            default => 'Lundi'
        };
    }
}