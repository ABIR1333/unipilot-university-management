<?php
namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\{JournalPedagogique, Module};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller {
    public function index(Request $request) {
        $professeur = Auth::user()->professeur;
        $modules    = $professeur->modules()->with('programme')->get();

        $selectedModule = $request->module_id
            ? Module::find($request->module_id)
            : $modules->first();

        $entrees = collect();
        if ($selectedModule) {
            $entrees = JournalPedagogique::where('professeur_id', $professeur->id)
                ->where('module_id', $selectedModule->id)
                ->orderByDesc('date')
                ->get();
        }

        return view('professeur.journal', compact('modules','selectedModule','entrees'));
    }

    public function create() {
        $professeur = Auth::user()->professeur;
        $modules = $professeur->modules()->with('programme')->get();
        
        return view('professeur.journal-create', compact('modules'));
    }

    public function edit($id) {
        $professeur = Auth::user()->professeur;
        $modules = $professeur->modules()->with('programme')->get();
        
        $entree = JournalPedagogique::where('id', $id)
            ->where('professeur_id', $professeur->id)
            ->firstOrFail();
        
        $selectedModule = $entree->module;
        
        return view('professeur.journal-edit', compact('modules', 'selectedModule', 'entree'));
    }

    public function store(Request $request) {
        $professeur = Auth::user()->professeur;

        $v = $request->validate([
            'module_id'    => 'required|exists:modules,id',
            'titre'        => 'required|string|max:255',
            'type_seance'  => 'required|in:CM,TD,TP',
            'date'         => 'required|date',
            'heure_debut'  => 'required',
            'heure_fin'    => 'required',
            'salle'        => 'nullable|string|max:100',
            'notes'        => 'nullable|string',
            'presents'     => 'nullable|integer|min:0',
            'total_inscrits'=> 'nullable|integer|min:0',
        ]);

        if (!$professeur->modules->pluck('id')->contains($v['module_id'])) abort(403);

        JournalPedagogique::create(array_merge($v, ['professeur_id' => $professeur->id]));

        return redirect()->back()->with('success', 'Séance ajoutée au journal.');
    }

    public function update(Request $request, $id) {
        $journal = JournalPedagogique::findOrFail($id);
        abort_if($journal->professeur_id !== Auth::user()->professeur->id, 403);

        $v = $request->validate([
            'titre'  => 'required|string|max:255',
            'notes'  => 'nullable|string',
        ]);

        $journal->update($v);
        return redirect()->route('admin.professeur.journal.index')->with('success', 'Séance modifiée.');
    }

    public function destroy($id) {
        $journal = JournalPedagogique::findOrFail($id);
        abort_if($journal->professeur_id !== Auth::user()->professeur->id, 403);
        $journal->delete();
        return redirect()->back()->with('success', 'Séance supprimée.');
    }
}