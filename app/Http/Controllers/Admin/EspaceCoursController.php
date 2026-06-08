<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Module, Annonce, DocumentCours, Commentaire};
use Illuminate\Http\Request;

class EspaceCoursController extends Controller {
    public function index(Request $request) {
        $modules = Module::with('programme')->where('is_active',true)->get();
        $selectedModule = $request->module_id ? Module::find($request->module_id) : $modules->first();

        $annonces    = collect();
        $documents   = collect();
        $commentaires= collect();

        if ($selectedModule) {
            $annonces  = $selectedModule->annonces()->with('creator')->latest()->get();
            $documents = $selectedModule->documents()->with('uploadedBy')->latest()->get();
            $commentaires = Commentaire::whereHas('annonce', fn($q) => $q->where('module_id',$selectedModule->id))
                ->with(['user','annonce'])->latest()->take(10)->get();
        }

        return view('admin.espace-cours.index', compact('modules','selectedModule','annonces','documents','commentaires'));
    }

    public function storeAnnonce(Request $request) {
        $v = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'titre'     => 'required|string|max:255',
            'contenu'   => 'required|string',
            'audience'  => 'required|in:tous,etudiants,professeurs',
        ]);
        Annonce::create(array_merge($v,['created_by'=>auth()->id()]));
        return redirect()->back()->with('success','Annonce publiée.');
    }

    public function destroyAnnonce(Annonce $annonce) {
        $annonce->delete();
        return redirect()->back()->with('success','Annonce supprimée.');
    }

    public function storeDocument(Request $request) {
        $v = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'titre'     => 'required|string|max:255',
            'fichier'   => 'required|file|max:20480',
        ]);
        $file = $request->file('fichier');
        $path = $file->store('documents_cours','public');
        DocumentCours::create([
            'module_id'   => $v['module_id'],
            'uploaded_by' => auth()->id(),
            'titre'       => $v['titre'],
            'fichier'     => $path,
            'nom_fichier' => $file->getClientOriginalName(),
            'taille'      => $file->getSize(),
        ]);
        return redirect()->back()->with('success','Document uploadé.');
    }

    public function destroyDocument(DocumentCours $document) {
        \Storage::disk('public')->delete($document->fichier);
        $document->delete();
        return redirect()->back()->with('success','Document supprimé.');
    }

    public function storeCommentaire(Request $request) {
        $v = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'contenu'    => 'required|string',
        ]);
        Commentaire::create(array_merge($v,['user_id'=>auth()->id()]));
        return redirect()->back()->with('success','Commentaire ajouté.');
    }
}
