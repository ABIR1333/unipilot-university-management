<?php
namespace App\Http\Controllers\Professeur;
use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploiDuTempsController extends Controller {
    public function index() {
        $professeur  = Auth::user()->professeur;
        $jours       = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];
        $emploiSemaine = EmploiDuTemps::where('professeur_id', $professeur->id)
            ->with(['module.programme','salle'])
            ->get()->groupBy('jour');

        return view('professeur.emploi-du-temps', compact('emploiSemaine','jours'));
    }
}
