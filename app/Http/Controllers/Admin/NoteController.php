<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Note, Module, Etudiant, Programme, Inscription};
use Illuminate\Http\Request;

class NoteController extends Controller {
    public function index(Request $request) {
        $modules = Module::with('programme')->where('is_active',true)->get();
        $selectedModule = $request->module_id ? Module::find($request->module_id) : $modules->first();
        $annee = $request->annee ?? '2024-2025';

        $notes = collect();
        $statsClasse = [];
        if ($selectedModule) {
            $notes = Note::with(['etudiant.user'])
                ->where('module_id',$selectedModule->id)
                ->where('annee_academique',$annee)
                ->get();

            if ($notes->count() > 0) {
                $notesFinales = $notes->whereNotNull('note_finale');
                $statsClasse = [
                    'moyenne' => round($notesFinales->avg('note_finale')??0, 1),
                    'taux_reussite' => $notesFinales->count()>0 ? round($notesFinales->where('note_finale','>=',10)->count()/$notesFinales->count()*100,1) : 0,
                    'note_max' => $notesFinales->max('note_finale') ?? 0,
                    'note_min' => $notesFinales->min('note_finale') ?? 0,
                ];
            }
        }

        return view('admin.notes.index', compact('modules','selectedModule','notes','annee','statsClasse'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'etudiant_id'=>'required|exists:etudiants,id',
            'module_id'=>'required|exists:modules,id',
            'annee_academique'=>'required|string',
            'semestre'=>'required|integer',
            'note_cc1'=>'nullable|numeric|min:0|max:20',
            'note_cc2'=>'nullable|numeric|min:0|max:20',
            'note_examen'=>'nullable|numeric|min:0|max:20',
        ]);
        $note = Note::updateOrCreate(
            ['etudiant_id'=>$v['etudiant_id'],'module_id'=>$v['module_id'],'annee_academique'=>$v['annee_academique']],
            $v
        );
        $note->calculerEtSauvegarder();
        return redirect()->back()->with('success','Note enregistrée.');
    }

    public function update(Request $request, Note $note) {
        $v = $request->validate([
            'note_cc1'=>'nullable|numeric|min:0|max:20',
            'note_cc2'=>'nullable|numeric|min:0|max:20',
            'note_examen'=>'nullable|numeric|min:0|max:20',
        ]);
        $note->fill($v);
        $note->calculerEtSauvegarder();
        return redirect()->back()->with('success','Note mise à jour.');
    }

    public function bulkUpdate(Request $request)
{
    $v = $request->validate([
        'notes' => 'required|array',

        'notes.*.note_id' => 'nullable|exists:notes,id',
        'notes.*.etudiant_id' => 'required|exists:etudiants,id',
        'notes.*.module_id' => 'required|exists:modules,id',
        'notes.*.annee_academique' => 'required|string',
        'notes.*.semestre' => 'required|integer',

        'notes.*.note_cc1' => 'nullable|numeric|min:0|max:20',
        'notes.*.note_cc2' => 'nullable|numeric|min:0|max:20',
        'notes.*.note_examen' => 'nullable|numeric|min:0|max:20',
    ]);

    foreach ($v['notes'] as $nd) {

        $noteId = $nd['note_id'] ?? null;

        unset($nd['note_id']);

        if ($noteId) {

            $note = Note::findOrFail($noteId);

            $note->fill([
                'etudiant_id'       => $nd['etudiant_id'],
                'module_id'         => $nd['module_id'],
                'annee_academique'  => $nd['annee_academique'],
                'semestre'          => $nd['semestre'],
                'note_cc1'          => $nd['note_cc1'] ?? null,
                'note_cc2'          => $nd['note_cc2'] ?? null,
                'note_examen'       => $nd['note_examen'] ?? null,
            ]);

        } else {

            $note = Note::firstOrNew([
                'etudiant_id'      => $nd['etudiant_id'],
                'module_id'        => $nd['module_id'],
                'annee_academique' => $nd['annee_academique'],
            ]);

            $note->fill([
                'etudiant_id'       => $nd['etudiant_id'],
                'module_id'         => $nd['module_id'],
                'annee_academique'  => $nd['annee_academique'],
                'semestre'          => $nd['semestre'],
                'note_cc1'          => $nd['note_cc1'] ?? null,
                'note_cc2'          => $nd['note_cc2'] ?? null,
                'note_examen'       => $nd['note_examen'] ?? null,
            ]);
        }

        $note->calculerEtSauvegarder();
    }

    return redirect()
        ->back()
        ->with('success', 'Notes sauvegardées avec succès.');
}

    public function exportPdf(Request $request) {
        $moduleId = $request->module_id;
        $annee = $request->annee ?? '2024-2025';
        $module = Module::with('programme')->findOrFail($moduleId);
        $notes = Note::with('etudiant.user')->where('module_id',$moduleId)->where('annee_academique',$annee)->get();
        $statsClasse = [
            'moyenne'=>round($notes->whereNotNull('note_finale')->avg('note_finale')??0,1),
            'taux_reussite'=>$notes->count()>0?round($notes->where('note_finale','>=',10)->count()/$notes->count()*100,1):0,
            'note_max'=>$notes->max('note_finale')??0,
            'note_min'=>$notes->min('note_finale')??0,
        ];
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.notes', compact('module','notes','annee','statsClasse'));
        $pdf->setPaper('A4','landscape');
        return $pdf->download("notes_{$module->code}_{$annee}.pdf");
    }
}
