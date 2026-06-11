<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmploiDuTemps;
use App\Models\Presence;
use App\Models\Inscription;
use App\Models\Etudiant;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $professeur = Auth::user()->professeur;
        
        if (!$professeur) {
            abort(403, 'Accès non autorisé');
        }
        
        // Récupérer les séances (cours) du professeur
        $seances = EmploiDuTemps::where('professeur_id', $professeur->id)
            ->with(['module', 'salle'])
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get()
            ->map(function($seance) {
                return [
                    'id' => $seance->id,
                    'label' => $seance->module->code . ' - ' . $seance->jour . ' ' . $seance->heure_debut . ' - ' . $seance->salle->nom
                ];
            });
        
        $selectedSeance = null;
        $etudiants = collect();
        $presences = collect();
        
        // Si une séance est sélectionnée
        if ($request->has('seance_id') && $request->seance_id) {
            $selectedSeance = EmploiDuTemps::with(['module', 'salle'])
                ->find($request->seance_id);
            
            if ($selectedSeance && $selectedSeance->professeur_id == $professeur->id) {
                // Récupérer les étudiants inscrits au module
                $etudiants = Inscription::where('module_id', $selectedSeance->module_id)
                    ->with('etudiant.user')
                    ->get()
                    ->pluck('etudiant');
                
                // Récupérer les présences existantes pour cette séance
                $presences = Presence::where('emploi_du_temps_id', $selectedSeance->id)
                    ->get()
                    ->keyBy('etudiant_id');
            }
        }
        
        return view('professeur.presences', compact(
            'seances',
            'selectedSeance',
            'etudiants',
            'presences'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'seance_id' => 'required|exists:emploi_du_temps,id',
            'presences' => 'array',
        ]);
        
        $professeur = Auth::user()->professeur;
        $seance = EmploiDuTemps::find($request->seance_id);
        
        if (!$seance || $seance->professeur_id != $professeur->id) {
            abort(403);
        }
        
        // Enregistrer les présences
        foreach ($request->presences as $etudiantId => $status) {
            Presence::updateOrCreate(
                [
                    'emploi_du_temps_id' => $seance->id,
                    'etudiant_id' => $etudiantId,
                ],
                [
                    'statut' => $status,
                    'justifiee' => $status === 'absent' ? ($request->justifiee[$etudiantId] ?? false) : false,
                ]
            );
        }
        
        return redirect()->route('admin.professeur.presences.index', ['seance_id' => $seance->id])
            ->with('success', 'Présences enregistrées avec succès !');
    }
}