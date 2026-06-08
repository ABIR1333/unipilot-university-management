<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Programme, Module, Professeur};
use Illuminate\Http\Request;

class ProgrammeController extends Controller {
    public function index() {
        $programmes = Programme::withCount(['etudiants','modules'])->latest()->get();
        $selectedProgramme = $programmes->first();
        $modules = $selectedProgramme ? Module::with('professeurs.user')->where('programme_id',$selectedProgramme->id)->get() : collect();
        return view('admin.programmes.index', compact('programmes','selectedProgramme','modules'));
    }

    public function modules(Programme $programme) {
        $modules = Module::with('professeurs.user')->where('programme_id',$programme->id)->get();
        return response()->json(['modules'=>$modules,'programme'=>$programme]);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'nom'=>'required|string|max:255',
            'code'=>'required|string|max:20|unique:programmes,code',
            'type'=>'required|in:Licence,Master,DUT,BTS,Doctorat',
            'duree_annees'=>'required|integer|min:1|max:8',
            'description'=>'nullable|string',
        ]);
        Programme::create($v);
        return redirect()->route('admin.programmes.index')->with('success','Programme créé.');
    }

    public function storeModule(Request $request) {
        $v = $request->validate([
            'programme_id'=>'required|exists:programmes,id',
            'nom'=>'required|string|max:255',
            'code'=>'nullable|string|max:20',
            'semestre_type'=>'required|in:Impair,Pair',
            'heures'=>'required|integer|min:1',
            'credits'=>'required|integer|min:1',
            'professeurs'=>'nullable|array',
        ]);
        $module = Module::create($v);
        if (!empty($v['professeurs'])) $module->professeurs()->sync($v['professeurs']);
        return redirect()->route('admin.programmes.index')->with('success','Module créé.');
    }

    public function updateModule(Request $request, Module $module) {
        $v = $request->validate([
            'nom'=>'required|string|max:255',
            'semestre_type'=>'required|in:Impair,Pair',
            'heures'=>'required|integer|min:1',
            'credits'=>'required|integer|min:1',
            'professeurs'=>'nullable|array',
        ]);
        $module->update($v);
        $module->professeurs()->sync($v['professeurs']??[]);
        return redirect()->route('admin.programmes.index')->with('success','Module modifié.');
    }

    public function destroyModule(Module $module) {
        $module->delete();
        return redirect()->route('admin.programmes.index')->with('success','Module supprimé.');
    }

    public function destroy(Programme $programme) {
        $programme->delete();
        return redirect()->route('admin.programmes.index')->with('success','Programme supprimé.');
    }
}
