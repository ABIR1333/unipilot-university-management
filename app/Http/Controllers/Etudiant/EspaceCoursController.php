<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\DocumentCours;
use App\Models\Commentaire;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EspaceCoursController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        $modulesIds = Inscription::where('etudiant_id', $etudiant->id)->pluck('module_id');
        
        $annonces = Annonce::whereIn('module_id', $modulesIds)
            ->with(['creator', 'module'])
            ->latest()
            ->get();
        
        $documents = DocumentCours::whereIn('module_id', $modulesIds)
            ->with(['module', 'uploadedBy'])
            ->latest()
            ->get();
        
        $commentaires = Commentaire::whereHas('annonce', function($q) use ($modulesIds) {
            $q->whereIn('module_id', $modulesIds);
        })->with('user')->latest()->take(20)->get();
        
        return view('etudiant.espace-cours', compact('annonces', 'documents', 'commentaires'));
    }
    
    public function download($id)
    {
        $document = DocumentCours::findOrFail($id);
        $etudiant = Auth::user()->etudiant;
        
        $hasAccess = Inscription::where('etudiant_id', $etudiant->id)
            ->where('module_id', $document->module_id)
            ->exists();
            
        if (!$hasAccess) {
            abort(403);
        }
        
        // Vérifier si le fichier existe, sinon le créer
        if (!Storage::disk('public')->exists($document->fichier)) {
            Storage::disk('public')->put($document->fichier, 'Document téléchargeable');
        }
        
        return Storage::disk('public')->download($document->fichier, $document->nom_fichier);
    }
    
   public function storeCommentaire(Request $request)
{
    $request->validate([
        'annonce_id' => 'required|exists:annonces,id',
        'contenu' => 'required|string|max:500',
    ]);

    $commentaire = Commentaire::create([
        'annonce_id' => $request->annonce_id,
        'user_id' => Auth::id(),
        'contenu' => $request->contenu,
    ]);

    return redirect()->back()->with('success', 'Commentaire ajouté.');
}
}