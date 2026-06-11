<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;

class EmploiDuTempsController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        $modulesIds = Inscription::where('etudiant_id', $etudiant->id)->pluck('module_id');
        
        $emplois = EmploiDuTemps::whereIn('module_id', $modulesIds)
            ->with(['module', 'salle', 'professeur.user'])
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();
        
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        
        $emploiSemaine = [];
        foreach ($jours as $jour) {
            $emploiSemaine[$jour] = $emplois->where('jour', $jour);
        }
        
        return view('etudiant.emploi-du-temps', compact('emploiSemaine', 'jours'));
    }
}