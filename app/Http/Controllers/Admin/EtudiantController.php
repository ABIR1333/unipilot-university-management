<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Etudiant, User, Programme, Note, Module};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Storage};

class EtudiantController extends Controller {
    public function index(Request $request) {
        $query = Etudiant::with(['user','programme'])
            ->when($request->search, fn($q) => $q->whereHas('user', fn($uq) =>
                $uq->where('name','like',"%{$request->search}%")->orWhere('email','like',"%{$request->search}%")
            )->orWhere('numero_carte','like',"%{$request->search}%"))
            ->when($request->programme_id, fn($q) => $q->where('programme_id',$request->programme_id))
            ->when($request->statut, fn($q) => $q->where('statut',$request->statut))
            ->when($request->semestre, fn($q) => $q->where('semestre_actuel',$request->semestre))
            ->latest();

        $etudiants  = $query->paginate(20)->withQueryString();
        $programmes = Programme::where('is_active',true)->get();
        $total      = Etudiant::count();
        return view('admin.etudiants.index', compact('etudiants','programmes','total'));
    }

    public function create() {
        $programmes = Programme::where('is_active',true)->get();
        return view('admin.etudiants.create', compact('programmes'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'programme_id'=>'required|exists:programmes,id',
            'semestre_actuel'=>'required|integer|min:1|max:12',
            'date_inscription'=>'required|date',
            'avatar'=>'nullable|image|max:2048',
        ]);

        DB::transaction(function() use ($v, $request) {
            $avatar = $request->hasFile('avatar') ? $request->file('avatar')->store('avatars','public') : null;
            $user = User::create(['name'=>$v['name'],'email'=>$v['email'],'password'=>Hash::make($v['password']),'avatar'=>$avatar]);
            $user->assignRole('etudiant');
            $num = 'ETU'.date('Y').str_pad(Etudiant::count()+1,4,'0',STR_PAD_LEFT);
            Etudiant::create(['user_id'=>$user->id,'programme_id'=>$v['programme_id'],'numero_carte'=>$num,'semestre_actuel'=>$v['semestre_actuel'],'date_inscription'=>$v['date_inscription']]);
        });
        return redirect()->route('admin.etudiants.index')->with('success','Étudiant créé avec succès.');
    }

    public function show(Etudiant $etudiant) {
        $etudiant->load(['user','programme','notes.module','presences.module','demandes']);
        $notesByModule = $etudiant->notes()->with('module')->get()->groupBy('module_id');
        $statsPresence = [
            'total'=>$etudiant->presences->count(),
            'presents'=>$etudiant->presences->where('statut','Présent')->count(),
            'absents'=>$etudiant->presences->where('statut','Absent')->count(),
            'justifies'=>$etudiant->presences->where('statut','Justifié')->count(),
        ];
        $statsPresence['taux'] = $statsPresence['total']>0 ? round($statsPresence['presents']/$statsPresence['total']*100) : 0;
        return view('admin.etudiants.show', compact('etudiant','notesByModule','statsPresence'));
    }

    public function edit(Etudiant $etudiant) {
        $programmes = Programme::where('is_active',true)->get();
        $etudiant->load('user');
        return view('admin.etudiants.edit', compact('etudiant','programmes'));
    }

    public function update(Request $request, Etudiant $etudiant) {
        $v = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>"required|email|unique:users,email,{$etudiant->user_id}",
            'programme_id'=>'required|exists:programmes,id',
            'semestre_actuel'=>'required|integer|min:1|max:12',
            'statut'=>'required|in:Actif,Suspendu,Diplômé,Retiré',
            'avatar'=>'nullable|image|max:2048',
        ]);
        DB::transaction(function() use ($v, $request, $etudiant) {
            if ($request->hasFile('avatar')) {
                if ($etudiant->user->avatar) Storage::disk('public')->delete($etudiant->user->avatar);
                $v['avatar'] = $request->file('avatar')->store('avatars','public');
            }
            $etudiant->user->update(['name'=>$v['name'],'email'=>$v['email'],'avatar'=>$v['avatar']??$etudiant->user->avatar]);
            $etudiant->update(['programme_id'=>$v['programme_id'],'semestre_actuel'=>$v['semestre_actuel'],'statut'=>$v['statut']]);
        });
        return redirect()->route('admin.etudiants.show',$etudiant)->with('success','Étudiant modifié.');
    }

    public function destroy(Etudiant $etudiant) {
        DB::transaction(function() use ($etudiant) {
            $etudiant->delete();
            $etudiant->user->delete();
        });
        return redirect()->route('admin.etudiants.index')->with('success','Étudiant supprimé.');
    }

    public function releve(Etudiant $etudiant) {
        $etudiant->load(['user','programme','notes.module']);
        $notesBySemestre = $etudiant->notes()->with('module')->get()->groupBy('semestre');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.releve-notes', compact('etudiant','notesBySemestre'));
        $pdf->setPaper('A4','portrait');
        return $pdf->download("releve_{$etudiant->numero_carte}.pdf");
    }

    public function attestation(Etudiant $etudiant) {
        $etudiant->load(['user','programme']);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.attestation', compact('etudiant'));
        $pdf->setPaper('A4','portrait');
        return $pdf->download("attestation_{$etudiant->numero_carte}.pdf");
    }
}
