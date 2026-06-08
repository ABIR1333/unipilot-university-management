<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Professeur, User, Module, Programme};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Storage};

class ProfesseurController extends Controller {
    public function index(Request $request) {
        $professeurs = Professeur::with(['user','modules'])
            ->when($request->search, fn($q) => $q->whereHas('user', fn($uq) =>
                $uq->where('name','like',"%{$request->search}%")))
            ->when($request->statut, fn($q) => $q->where('statut',$request->statut))
            ->latest()->paginate(20)->withQueryString();
        return view('admin.professeurs.index', compact('professeurs'));
    }

    public function create() {
        $modules = Module::with('programme')->where('is_active',true)->get();
        return view('admin.professeurs.create', compact('modules'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'specialite'=>'nullable|string|max:255',
            'bureau'=>'nullable|string|max:100',
            'telephone'=>'nullable|string|max:20',
            'date_embauche'=>'required|date',
            'modules'=>'nullable|array',
            'avatar'=>'nullable|image|max:2048',
        ]);
        DB::transaction(function() use ($v, $request) {
            $avatar = $request->hasFile('avatar') ? $request->file('avatar')->store('avatars','public') : null;
            $user = User::create(['name'=>$v['name'],'email'=>$v['email'],'password'=>Hash::make($v['password']),'avatar'=>$avatar]);
            $user->assignRole('professeur');
            $empId = 'PROF'.date('Y').str_pad(Professeur::count()+1,3,'0',STR_PAD_LEFT);
            $prof = Professeur::create(['user_id'=>$user->id,'employee_id'=>$empId,'specialite'=>$v['specialite']??null,'bureau'=>$v['bureau']??null,'telephone'=>$v['telephone']??null,'date_embauche'=>$v['date_embauche']]);
            if (!empty($v['modules'])) $prof->modules()->sync($v['modules']);
        });
        return redirect()->route('admin.professeurs.index')->with('success','Professeur créé avec succès.');
    }

    public function show(Professeur $professeur) {
        $professeur->load(['user','modules.programme']);
        return view('admin.professeurs.show', compact('professeur'));
    }

    public function edit(Professeur $professeur) {
        $modules = Module::with('programme')->where('is_active',true)->get();
        $professeur->load('user','modules');
        return view('admin.professeurs.edit', compact('professeur','modules'));
    }

    public function update(Request $request, Professeur $professeur) {
        $v = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>"required|email|unique:users,email,{$professeur->user_id}",
            'specialite'=>'nullable|string|max:255',
            'bureau'=>'nullable|string|max:100',
            'telephone'=>'nullable|string|max:20',
            'statut'=>'required|in:Actif,Congé,Inactif',
            'modules'=>'nullable|array',
            'avatar'=>'nullable|image|max:2048',
        ]);
        DB::transaction(function() use ($v, $request, $professeur) {
            if ($request->hasFile('avatar')) {
                if ($professeur->user->avatar) Storage::disk('public')->delete($professeur->user->avatar);
                $v['avatar'] = $request->file('avatar')->store('avatars','public');
            }
            $professeur->user->update(['name'=>$v['name'],'email'=>$v['email'],'avatar'=>$v['avatar']??$professeur->user->avatar]);
            $professeur->update(['specialite'=>$v['specialite']??null,'bureau'=>$v['bureau']??null,'telephone'=>$v['telephone']??null,'statut'=>$v['statut']]);
            $professeur->modules()->sync($v['modules']??[]);
        });
        return redirect()->route('admin.professeurs.show',$professeur)->with('success','Professeur modifié.');
    }

    public function destroy(Professeur $professeur) {
        DB::transaction(function() use ($professeur) { $professeur->delete(); $professeur->user->delete(); });
        return redirect()->route('admin.professeurs.index')->with('success','Professeur supprimé.');
    }
}
