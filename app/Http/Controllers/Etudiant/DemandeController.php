<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        $mesDemandes = Demande::where('etudiant_id', $etudiant->id)
            ->latest()
            ->get();
        
        $typesDemandes = [
            ['type' => 'Attestation de scolarité', 'desc' => 'Justificatif d\'inscription', 'delai' => '48h'],
            ['type' => 'Relevé de notes', 'desc' => 'Résultats officiels', 'delai' => '72h'],
            ['type' => 'Certificat d\'inscription', 'desc' => 'Pour démarches administratives', 'delai' => '24h'],
        ];
        
        return view('etudiant.demandes', compact('mesDemandes', 'typesDemandes'));
    }
    
    public function store(Request $request)
    {
        $etudiant = Auth::user()->etudiant;
        
        $request->validate([
            'type' => 'required|string',
            'motif' => 'nullable|string',
        ]);
        
        Demande::create([
            'etudiant_id' => $etudiant->id,
            'type' => $request->type,
            'motif' => $request->motif,
            'statut' => 'En attente',
        ]);
        
        return redirect()->back()->with('success', 'Demande soumise avec succès.');
    }
    
    public function telecharger(Demande $demande)
    {
        // Vérifier que la demande appartient à l'étudiant connecté
        if ($demande->etudiant_id !== Auth::user()->etudiant->id) {
            abort(403);
        }
        
        // Générer un PDF simple
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML('
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; margin: 40px; }
                    h1 { color: #4f46e5; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .content { margin-top: 20px; }
                    .label { font-weight: bold; width: 150px; display: inline-block; }
                    .value { margin-bottom: 10px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>UniPilot</h1>
                    <h2>Demande administrative</h2>
                </div>
                <div class="content">
                    <div class="value"><span class="label">Type :</span> ' . $demande->type . '</div>
                    <div class="value"><span class="label">Date :</span> ' . $demande->created_at->format('d/m/Y') . '</div>
                    <div class="value"><span class="label">Statut :</span> ' . $demande->statut . '</div>
                    <div class="value"><span class="label">Motif :</span> ' . ($demande->motif ?? 'Non spécifié') . '</div>
                </div>
            </body>
            </html>
        ');
        
        return $pdf->download('demande_' . $demande->id . '.pdf');
    }
}