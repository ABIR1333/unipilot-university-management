<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        $presences = Presence::where('etudiant_id', $etudiant->id)
            ->with(['module'])
            ->get();
        
        $totalSeances = $presences->count();
        $absences = $presences->where('statut', 'Absent')->count();
        
        $parModule = [];
        $inscriptions = Inscription::where('etudiant_id', $etudiant->id)->with('module')->get();
        
        foreach ($inscriptions as $inscription) {
            $modulePresences = $presences->filter(function($p) use ($inscription) {
                return $p->module_id == $inscription->module_id;
            });
            $total = $modulePresences->count();
            $abs = $modulePresences->where('statut', 'Absent')->count();
            
            if ($total > 0) {
                $parModule[] = [
                    'module' => $inscription->module,
                    'total' => $total,
                    'absences' => $abs,
                    'justifiees' => $modulePresences->where('justification', '!=', null)->count(),
                    'taux' => $total > 0 ? round(($total - $abs) / $total * 100, 1) : 0
                ];
            }
        }
        
        return view('etudiant.presences', compact('totalSeances', 'absences', 'parModule'));
    }
}