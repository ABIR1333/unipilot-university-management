<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Presence, Module, Etudiant, Inscription};
use Illuminate\Http\Request;

class PresenceController extends Controller {
    public function index(Request $request) {
        $modules = Module::with('programme')->where('is_active',true)->get();
        $selectedModule = $request->module_id ? Module::find($request->module_id) : $modules->first();
        $date = $request->date ?? today()->toDateString();

        $presences = collect();
        $statsGlobales = [];
        if ($selectedModule) {
            // Inscrits dans ce module
            $etudiants = Etudiant::whereHas('inscriptions', fn($q) => $q->where('module_id',$selectedModule->id))
                ->with('user')->get();

            $presencesExistantes = Presence::where('module_id',$selectedModule->id)->get();

            $statsGlobales = [
                'presents_today' => $presencesExistantes->where('date',$date)->where('statut','Présent')->count(),
                'absents_today'  => $presencesExistantes->where('date',$date)->where('statut','Absent')->count(),
                'justifies_today'=> $presencesExistantes->where('date',$date)->where('statut','Justifié')->count(),
                'taux_global'    => $presencesExistantes->count()>0
                    ? round($presencesExistantes->where('statut','Présent')->count()/$presencesExistantes->count()*100,1) : 0,
            ];

            $presences = $etudiants->map(function($e) use ($presencesExistantes) {
                $ep = $presencesExistantes->where('etudiant_id',$e->id);
                $total = $ep->count();
                $pres  = $ep->where('statut','Présent')->count();
                return [
                    'etudiant'=>$e,
                    'total'=>$total, 'presents'=>$pres,
                    'absents'=>$ep->where('statut','Absent')->count(),
                    'justifies'=>$ep->where('statut','Justifié')->count(),
                    'taux'=>$total>0?round($pres/$total*100,1):0,
                    'alerte'=>$total>0&&($pres/$total)<0.75,
                ];
            });
        }

        return view('admin.presences.index', compact('modules','selectedModule','date','presences','statsGlobales'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'module_id'=>'required|exists:modules,id',
            'date'=>'required|date',
            'presences'=>'required|array',
            'presences.*.etudiant_id'=>'required|exists:etudiants,id',
            'presences.*.statut'=>'required|in:Présent,Absent,Justifié',
            'presences.*.justification'=>'nullable|string',
        ]);
        foreach ($v['presences'] as $p) {
            Presence::updateOrCreate(
                ['etudiant_id'=>$p['etudiant_id'],'module_id'=>$v['module_id'],'date'=>$v['date']],
                ['statut'=>$p['statut'],'justification'=>$p['justification']??null]
            );
        }
        return redirect()->back()->with('success','Présences enregistrées.');
    }

    public function exportPdf(Request $request) {
        $module = Module::findOrFail($request->module_id);
        $etudiants = Etudiant::whereHas('inscriptions', fn($q) => $q->where('module_id',$module->id))->with('user')->get();
        $presences = Presence::where('module_id',$module->id)->get();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.presences', compact('module','etudiants','presences'));
        $pdf->setPaper('A4','landscape');
        return $pdf->download("presences_{$module->code}.pdf");
    }
}
