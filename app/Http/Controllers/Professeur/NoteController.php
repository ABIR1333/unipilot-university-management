<?php
namespace App\Http\Controllers\Professeur;
use App\Http\Controllers\Controller;
use App\Models\{Note, Module, Etudiant, Inscription};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller {
    public function index(Request $request) {
        $professeur = Auth::user()->professeur;
        $modules    = $professeur->modules()->with('programme')->get();

        $selectedModule = $request->module_id
            ? Module::find($request->module_id)
            : $modules->first();

        $notes = collect();
        $statsClasse = [];

        if ($selectedModule) {
            // Étudiants inscrits dans ce module
            $etudiants = Etudiant::whereHas('inscriptions', fn($q) =>
                $q->where('module_id', $selectedModule->id)
            )->with('user')->get();

            $notes = $etudiants->map(function($etudiant) use ($selectedModule) {
                $note = Note::where('etudiant_id', $etudiant->id)
                    ->where('module_id', $selectedModule->id)
                    ->first();
                return [
                    'etudiant' => $etudiant,
                    'note'     => $note,
                ];
            });

            $existingNotes = Note::where('module_id', $selectedModule->id)->get();
            if ($existingNotes->count() > 0) {
                $finales = $existingNotes->whereNotNull('note_finale');
                $statsClasse = [
                    'moyenne'       => round($finales->avg('note_finale') ?? 0, 1),
                    'taux_reussite' => $finales->count() > 0
                        ? round($finales->where('note_finale','>=',10)->count() / $finales->count() * 100, 1)
                        : 0,
                    'note_max'  => $finales->max('note_finale') ?? 0,
                    'note_min'  => $finales->min('note_finale') ?? 0,
                ];
            }
        }

        return view('professeur.notes', compact('modules','selectedModule','notes','statsClasse'));
    }

    public function save(Request $request) {
        $professeur = Auth::user()->professeur;

        $v = $request->validate([
            'notes'                    => 'required|array',
            'notes.*.etudiant_id'      => 'required|exists:etudiants,id',
            'notes.*.module_id'        => 'required|exists:modules,id',
            'notes.*.note_cc1'         => 'nullable|numeric|min:0|max:20',
            'notes.*.note_cc2'         => 'nullable|numeric|min:0|max:20',
            'notes.*.note_examen'      => 'nullable|numeric|min:0|max:20',
            'notes.*.annee_academique' => 'required|string',
            'notes.*.semestre'         => 'required|integer',
        ]);

        foreach ($v['notes'] as $nd) {
            // Verify module belongs to this professor
            if (!$professeur->modules->pluck('id')->contains($nd['module_id'])) continue;

            $note = Note::updateOrCreate(
                ['etudiant_id' => $nd['etudiant_id'], 'module_id' => $nd['module_id'], 'annee_academique' => $nd['annee_academique']],
                array_merge($nd, ['semestre' => $nd['semestre']])
            );
            $note->calculerEtSauvegarder();
        }

        return redirect()->back()->with('success', 'Notes enregistrées avec succès.');
    }

    public function exportPdf(Request $request) {
        $professeur = Auth::user()->professeur;
        $module     = Module::findOrFail($request->module_id);
        $annee      = $request->annee ?? '2024-2025';

        $notes = Note::with('etudiant.user')
            ->where('module_id', $module->id)
            ->where('annee_academique', $annee)
            ->get();

        $statsClasse = [
            'moyenne'       => round($notes->whereNotNull('note_finale')->avg('note_finale') ?? 0, 1),
            'taux_reussite' => $notes->count() > 0
                ? round($notes->where('note_finale','>=',10)->count() / $notes->count() * 100, 1) : 0,
            'note_max'  => $notes->max('note_finale') ?? 0,
            'note_min'  => $notes->min('note_finale') ?? 0,
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.notes', compact('module','notes','annee','statsClasse'));
        $pdf->setPaper('A4','landscape');
        return $pdf->download("notes_{$module->code}_{$annee}.pdf");
    }
}
