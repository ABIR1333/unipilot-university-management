<?php
namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesseurDemandeController extends Controller {
    public function index() {
        $professeur = Auth::user()->professeur;
        
        $mesDemandes = Demande::where('etudiant_id', $professeur->id)
            ->latest()
            ->get();

        $typesDemandes = [
            ['type' => "Attestation d'emploi",  'desc' => 'Justificatif de travail officiel',    'delai' => '72h'],
            ['type' => 'Ordre de mission',        'desc' => 'Déplacement pour conférence',         'delai' => '5 jours'],
            ['type' => 'Congé',                   'desc' => 'Demande de congé ou absence',         'delai' => '1 semaine'],
        ];

        return view('professeur.demandes', compact('mesDemandes', 'typesDemandes'));
    }

    public function store(Request $request) {
        $professeur = Auth::user()->professeur;
        
        $v = $request->validate([
            'type'  => 'required|string|max:100',
            'motif' => 'nullable|string',
        ]);

        Demande::create([
            'etudiant_id' => $professeur->id,
            'type'        => $v['type'],
            'motif'       => $v['motif'] ?? null,
            'statut'      => 'En attente',
        ]);

        return redirect()->back()->with('success', 'Demande soumise.');
    }

    public function telecharger(Demande $demande) {
        abort_if($demande->etudiant_id !== Auth::user()->professeur->id, 403);
        
        $professeur = $demande->professeur;
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.demande', compact('demande', 'professeur'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("demande_{$demande->id}.pdf");
    }
}