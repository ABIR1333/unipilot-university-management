<?php
namespace App\Http\Controllers\Professeur;
use App\Http\Controllers\Controller;
use App\Models\{Module, Annonce, DocumentCours, Commentaire};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};

class EspaceCoursController extends Controller {
    public function index(Request $request) {
        $professeur = Auth::user()->professeur;
        $modules    = $professeur->modules()->with('programme')->get();

        $selectedModule = $request->module_id
            ? Module::find($request->module_id)
            : $modules->first();

        $annonces    = collect();
        $documents   = collect();
        $commentaires= collect();

        if ($selectedModule) {
            $annonces  = $selectedModule->annonces()->with('creator')->latest()->get();
            $documents = $selectedModule->documents()->with('uploadedBy')->latest()->get();
            $commentaires = Commentaire::whereHas('annonce', fn($q) =>
                $q->where('module_id', $selectedModule->id)
            )->with('user')->latest()->take(10)->get();
        }

        return view('professeur.espace-cours', compact('modules','selectedModule','annonces','documents','commentaires'));
    }

    public function storeAnnonce(Request $request) {
        $professeur = Auth::user()->professeur;
        $v = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'titre'     => 'required|string|max:255',
            'contenu'   => 'required|string',
        ]);
        if (!$professeur->modules->pluck('id')->contains($v['module_id'])) abort(403);
        Annonce::create(array_merge($v, ['created_by' => Auth::id(), 'audience' => 'etudiants']));
        return redirect()->back()->with('success', 'Annonce publiée.');
    }

    public function destroyAnnonce(Annonce $annonce) {
        $professeur = Auth::user()->professeur;
        if ($annonce->created_by !== Auth::id()) abort(403);
        $annonce->delete();
        return redirect()->back()->with('success', 'Annonce supprimée.');
    }

    public function storeDocument(Request $request) {
        $professeur = Auth::user()->professeur;
        $v = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'titre'     => 'required|string|max:255',
            'fichier'   => 'required|file|max:20480',
        ]);
        if (!$professeur->modules->pluck('id')->contains($v['module_id'])) abort(403);
        $file = $request->file('fichier');
        $path = $file->store('documents_cours','public');
        DocumentCours::create([
            'module_id'   => $v['module_id'],
            'uploaded_by' => Auth::id(),
            'titre'       => $v['titre'],
            'fichier'     => $path,
            'nom_fichier' => $file->getClientOriginalName(),
            'taille'      => $file->getSize(),
        ]);
        return redirect()->back()->with('success', 'Document déposé.');
    }

    public function destroyDocument(DocumentCours $document) {
        if ($document->uploaded_by !== Auth::id()) abort(403);
        Storage::disk('public')->delete($document->fichier);
        $document->delete();
        return redirect()->back()->with('success', 'Document supprimé.');
    }

    public function storeCommentaire(Request $request) {
        $v = $request->validate(['annonce_id'=>'required|exists:annonces,id','contenu'=>'required|string']);
        Commentaire::create(array_merge($v, ['user_id' => Auth::id()]));
        return redirect()->back()->with('success', 'Réponse envoyée.');
    }
}
