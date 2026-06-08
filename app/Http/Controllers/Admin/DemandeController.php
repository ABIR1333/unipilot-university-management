<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Demande, Etudiant};
use Illuminate\Http\Request;

class DemandeController extends Controller {
    public function index(Request $request) {
        $statut  = $request->statut ?? 'En attente';
        $counts  = [
            'En attente' => Demande::where('statut','En attente')->count(),
            'Approuvée'  => Demande::where('statut','Approuvée')->count(),
            'Rejetée'    => Demande::where('statut','Rejetée')->count(),
        ];
        $demandes = Demande::with(['etudiant.user'])
            ->where('statut',$statut)
            ->latest()->get();
        return view('admin.demandes.index', compact('demandes','statut','counts'));
    }

    public function approuver(Request $request, Demande $demande) {
        $demande->update([
            'statut'            => 'Approuvée',
            'commentaire_admin' => $request->commentaire,
            'traite_par'        => auth()->id(),
            'traite_le'         => now(),
        ]);
        // Générer le PDF automatiquement
        $this->genererDocument($demande);
        return redirect()->back()->with('success','Demande approuvée.');
    }

    public function rejeter(Request $request, Demande $demande) {
        $demande->update([
            'statut'            => 'Rejetée',
            'commentaire_admin' => $request->commentaire,
            'traite_par'        => auth()->id(),
            'traite_le'         => now(),
        ]);
        return redirect()->back()->with('success','Demande rejetée.');
    }

    public function telecharger(Demande $demande) {
        if (!$demande->fichier_genere || !file_exists(storage_path('app/public/'.$demande->fichier_genere))) {
            $this->genererDocument($demande);
        }
        $etudiant = $demande->etudiant->load(['user','programme']);
        $pdf = app('dompdf.wrapper');
        $view = $demande->type === 'Relevé de notes' ? 'pdf.releve-notes' : 'pdf.attestation';
        if ($demande->type === 'Relevé de notes') {
            $notesBySemestre = $etudiant->notes()->with('module')->get()->groupBy('semestre');
            $pdf->loadView($view, compact('etudiant','notesBySemestre'));
        } else {
            $pdf->loadView($view, compact('etudiant'));
        }
        $pdf->setPaper('A4','portrait');
        $filename = strtolower(str_replace([' ','é','è','ê'],'_',$demande->type)).'_'.$etudiant->numero_carte.'.pdf';
        return $pdf->download($filename);
    }

    private function genererDocument(Demande $demande): void {
        $path = "demandes/demande_{$demande->id}.pdf";
        $demande->update(['fichier_genere' => $path]);
    }
}
