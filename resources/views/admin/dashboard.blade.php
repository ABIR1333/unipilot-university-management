@extends('layouts.app')
@section('title','Tableau de bord')
@section('page-title','Tableau de bord')

@section('content')
{{-- Header row --}}
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-900">Tableau de bord</h2>
    <span class="text-sm text-gray-400 flex items-center gap-1.5">
        <i class="fa-solid fa-rotate text-gray-400"></i> Dernière mise à jour : il y a 2 min
    </span>
</div>

{{-- Stats Row 1 --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                <i class="fa-solid fa-user-graduate text-indigo-600"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-trend-up"></i> 4.2%
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_etudiants']) }}</p>
        <p class="text-sm text-gray-600 mt-0.5">Étudiants inscrits</p>
        <p class="text-xs text-gray-400 mt-0.5">Année 2024–2025</p>
    </div>
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-chalkboard-user text-blue-600"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-trend-up"></i> 1.1%
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_professeurs'] }}</p>
        <p class="text-sm text-gray-600 mt-0.5">Professeurs actifs</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $stats['modules_non_couverts'] }} modules non couverts</p>
    </div>
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fa-solid fa-book-open text-purple-600"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_programmes'] }}</p>
        <p class="text-sm text-gray-600 mt-0.5">Programmes</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $stats['total_modules'] }} modules au total</p>
    </div>
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                <i class="fa-solid fa-file-lines text-orange-600"></i>
            </div>
            <span class="text-xs font-semibold text-red-500 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-trend-down"></i> 8.3%
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['demandes_attente'] }}</p>
        <p class="text-sm text-gray-600 mt-0.5">Demandes en attente</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $stats['demandes_urgentes'] }} urgentes</p>
    </div>
</div>

{{-- Stats Row 2 --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="card p-5">
        <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center mb-3">
            <i class="fa-solid fa-layer-group text-teal-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_modules'] }}</p>
        <p class="text-sm text-gray-600 mt-0.5">Modules actifs</p>
        <p class="text-xs text-gray-400 mt-0.5">12 semestres</p>
    </div>
    <div class="card p-5">
        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mb-3">
            <i class="fa-regular fa-building text-green-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['reservations_semaine'] }}</p>
        <p class="text-sm text-gray-600 mt-0.5">Réservations salles</p>
        <p class="text-xs text-gray-400 mt-0.5">Cette semaine</p>
    </div>
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-cyan-600"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-trend-up"></i> 2.1%
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['taux_presence'] }}%</p>
        <p class="text-sm text-gray-600 mt-0.5">Taux de présence</p>
        <p class="text-xs text-gray-400 mt-0.5">+2.1% vs mois dernier</p>
    </div>
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-pink-600"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-0.5">
                <i class="fa-solid fa-arrow-trend-up"></i> 0.8%
            </span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['moyenne_generale'] }}/20</p>
        <p class="text-sm text-gray-600 mt-0.5">Moyenne générale</p>
        <p class="text-xs text-gray-400 mt-0.5">Semestre 4</p>
    </div>
</div>

{{-- Charts + Activités --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
 {{-- Performance académique --}}
<div class="card p-5 xl:col-span-2">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="font-semibold text-gray-900">Performance académique</p>
            <p class="text-xs text-gray-400">Moyenne et présence — Sept. 2024 → Mars 2025</p>
        </div>

        <a href="{{ route('admin.rapports.export-pdf') }}" class="btn-secondary btn-sm">
            <i class="fa-solid fa-download"></i>
            Exporter
        </a>
    </div>

    <div class="relative h-[340px] w-full">
        <canvas id="perfChart"></canvas>
    </div>
</div>

    {{-- Activités récentes --}}
    <div class="card p-5">
        <p class="font-semibold text-gray-900 mb-0.5">Activités récentes</p>
        <p class="text-xs text-gray-400 mb-4">Dernières actions sur la plateforme</p>
        <div class="space-y-3">
            @foreach($activitesRecentes->take(5) as $activite)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-{{ $activite['color'] ?? 'indigo' }}-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid {{ $activite['icon'] ?? 'fa-bell' }} text-{{ $activite['color'] ?? 'indigo' }}-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-800 font-medium leading-tight">{{ $activite['texte'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $activite['temps'] }}</p>
                </div>
            </div>
            @endforeach
            @if($activitesRecentes->isEmpty())
            {{-- Default activities --}}
            @foreach([
                ['icon'=>'fa-user-graduate','color'=>'indigo','texte'=>'Inscription de Lucas Petit validée','temps'=>'Il y a 5 min'],
                ['icon'=>'fa-clipboard-list','color'=>'teal','texte'=>'Notes CC1 soumises — Dr. Dubois (Algorithmique)','temps'=>'Il y a 23 min'],
                ['icon'=>'fa-file-lines','color'=>'orange','texte'=>'Demande d\'attestation — Emma Bernard','temps'=>'Il y a 1h'],
                ['icon'=>'fa-door-open','color'=>'green','texte'=>'Salle B201 réservée — Examen partiel','temps'=>'Il y a 2h'],
                ['icon'=>'fa-star','color'=>'purple','texte'=>'Résultats Semestre 3 publiés','temps'=>'Hier 16:42'],
            ] as $act)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-{{ $act['color'] }}-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid {{ $act['icon'] }} text-{{ $act['color'] }}-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-800 font-medium leading-tight">{{ $act['texte'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $act['temps'] }}</p>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Moyennes + Mentions --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
    <div class="card p-5">
        <p class="font-semibold text-gray-900 mb-4">Moyennes par module</p>
       <div class="h-[300px]">
    <canvas id="modulesChart"></canvas>
</div>
    </div>
    <div class="card p-5">
        <p class="font-semibold text-gray-900 mb-4">Distribution des mentions</p>
        <div class="flex items-center gap-6">
            <div class="w-44 h-44 flex-shrink-0">
    <canvas id="mentionsChart"></canvas>
</div>
            <div class="space-y-2 flex-1">
                @php
                $mentionColors = ['Très Bien'=>'#22c55e','Bien'=>'#3b82f6','Assez Bien'=>'#06b6d4','Passable'=>'#f59e0b','Insuffisant'=>'#ef4444'];
                $mentionPcts = ['Très Bien'=>18,'Bien'=>28,'Assez Bien'=>31,'Passable'=>15,'Insuffisant'=>8];
                @endphp
                @foreach($mentionPcts as $label => $pct)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background:{{ $mentionColors[$label] }}"></div>
                        <span class="text-gray-600">{{ $label }}</span>
                    </div>
                    @php $dm = $distributionMentions->firstWhere('mention',$label); @endphp
                    <span class="font-semibold text-gray-900">{{ $dm ? round($dm->count / max($distributionMentions->sum('count'),1)*100) : $pct }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const mois = ['Sep','Oct','Nov','Déc','Jan','Fév','Mar'];
const perfData = @json($performanceMois);
const presData = @json($presenceMois);

new Chart(document.getElementById('perfChart'), {
    type:'line',
    data:{
        labels: perfData.length ? perfData.map(d => mois[(d.mois-1)%12]) : mois,
        datasets:[
            {label:'Moyenne /20', data: perfData.length ? perfData.map(d=>parseFloat(d.moyenne)) : [12,12.5,11.8,12.2,12.8,13.1,12.9],
             borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,0.08)', fill:true, tension:0.4, pointRadius:4, pointBackgroundColor:'#6366f1'},
            {label:'Présence %', data: presData.length ? presData.map(d=>d.taux) : [88,92,85,87,90,89,88],
             borderColor:'#06b6d4', backgroundColor:'rgba(6,182,212,0.08)', fill:true, tension:0.4, pointRadius:4, pointBackgroundColor:'#06b6d4'},
        ]
    },
options:{
    responsive:true,
        maintainAspectRatio:false,
  
    scales:{
        y:{
            beginAtZero:true,
            max:100,
            grid:{color:'#f3f4f6'}
        },
        x:{
            grid:{display:false}
        }
    },
    plugins:{
        legend:{
            position:'bottom',
            labels:{
                usePointStyle:true,
                pointStyleWidth:8
            }
        }
    }
}
});

const modules = @json($moyennesModules);
new Chart(document.getElementById('modulesChart'), {
    type:'bar',
    data:{
        labels: modules.length ? modules.map(m=>m.code||m.nom.substring(0,4)) : ['Algo','POO','BDD','Réseaux','Maths','Systèmes'],
        datasets:[{data: modules.length ? modules.map(m=>parseFloat(m.moy||0)) : [12.8,13.4,12.1,11.5,10.9,13.2],
            backgroundColor:'#6366f1', borderRadius:6, borderSkipped:false}]
    },
   options:{
    responsive:true,
        maintainAspectRatio:false,
   
    plugins:{
        legend:{
            display:false
        }
    },
    scales:{
        y:{
            beginAtZero:true,
            max:20,
            grid:{color:'#f3f4f6'}
        },
        x:{
            grid:{display:false}
        }
    }
}
});

const mData = @json($distributionMentions);
new Chart(document.getElementById('mentionsChart'), {
    type:'doughnut',
    data:{
        labels:['Très Bien','Bien','Assez Bien','Passable','Insuffisant'],
        datasets:[{data: mData.length ? ['Très Bien','Bien','Assez Bien','Passable','Insuffisant'].map(l=>{const d=mData.find(m=>m.mention===l);return d?d.count:1}) : [18,28,31,15,8],
            backgroundColor:['#22c55e','#3b82f6','#06b6d4','#f59e0b','#ef4444'], borderWidth:0}]
    },
options:{
    responsive:true,
        maintainAspectRatio:false,
  
    cutout:'70%',
    plugins:{
        legend:{
            display:false
        }
    }
}
});
</script>
@endpush
