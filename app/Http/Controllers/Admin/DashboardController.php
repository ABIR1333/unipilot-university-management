<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Etudiant, Professeur, Programme, Module, Demande, Note, Presence, ReservationSalle};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total_etudiants'   => Etudiant::where('statut','Actif')->count(),
            'total_professeurs' => Professeur::where('statut','Actif')->count(),
            'total_programmes'  => Programme::where('is_active',true)->count(),
            'total_modules'     => Module::where('is_active',true)->count(),
            'demandes_attente'  => Demande::where('statut','En attente')->count(),
            'demandes_urgentes' => Demande::where('statut','En attente')->where('created_at','>=',now()->subDays(2))->count(),
            'reservations_semaine' => ReservationSalle::whereBetween('date',[now()->startOfWeek(),now()->endOfWeek()])->count(),
            'modules_non_couverts' => Module::doesntHave('professeurs')->count(),
        ];

        // Taux de présence global
        $totalPresences = Presence::count();
        $presentsCount  = Presence::where('statut','Présent')->count();
        $stats['taux_presence'] = $totalPresences > 0 ? round($presentsCount/$totalPresences*100,1) : 0;

        // Moyenne générale
        $stats['moyenne_generale'] = round(Note::whereNotNull('note_finale')->avg('note_finale') ?? 0, 1);

        // Activités récentes
        $activitesRecentes = collect();
        $derniersDemandes = Demande::with('etudiant.user')->latest()->take(3)->get()->map(fn($d) => [
            'icon'=>'fa-file-alt','color'=>'indigo',
            'texte'=>"Demande {$d->type} — {$d->etudiant->nom}",'temps'=>$d->created_at->diffForHumans(),
        ]);
        $dernieresNotes = Note::with(['module','etudiant.user'])->latest()->take(2)->get()->map(fn($n) => [
            'icon'=>'fa-clipboard-list','color'=>'teal',
            'texte'=>"Notes {$n->module->nom} soumises",'temps'=>$n->updated_at->diffForHumans(),
        ]);
        $activitesRecentes = $derniersDemandes->merge($dernieresNotes)->sortByDesc('temps');

        // Performance académique (par mois)
        $performanceMois = Note::whereNotNull('note_finale')
            ->where('created_at','>=',now()->subMonths(7))
            ->selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, AVG(note_finale) as moyenne')
            ->groupBy('annee','mois')->orderBy('annee')->orderBy('mois')->get();

        // Presence par mois
        $presenceMois = Presence::where('created_at','>=',now()->subMonths(7))
            ->selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, SUM(statut="Présent") as presents, COUNT(*) as total')
            ->groupBy('annee','mois')->orderBy('annee')->orderBy('mois')->get()
            ->map(fn($p) => [...$p->toArray(), 'taux'=>$p->total>0?round($p->presents/$p->total*100):0]);

        // Moyennes par module
        $moyennesModules = Module::withAvg(['notes as moyenne_notes'=>fn($q)=>$q->whereNotNull('note_finale')],'note_finale')
            ->where('is_active',true)->take(6)->get();

        // Distribution mentions
        $distributionMentions = Note::whereNotNull('mention')
            ->selectRaw('mention, COUNT(*) as count')->groupBy('mention')->get();

        return view('admin.dashboard', compact(
            'stats','activitesRecentes','performanceMois','presenceMois','moyennesModules','distributionMentions'
        ));
    }
}
