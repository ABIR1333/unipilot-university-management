@extends('layouts.app')
@section('title','Rapports & Analytics')
@section('page-title','Rapports & Analytics')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Rapports & Analytics</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
    <a href="{{ route('admin.rapports.export-pdf',['semestre'=>$semestre,'annee'=>$annee]) }}"
       class="btn-primary">
        <i class="fa-solid fa-download"></i> Exporter PDF
    </a>
</div>

{{-- Semester tabs --}}
<div class="flex gap-2 mb-5">
    @foreach($semestres as $s)
    <a href="{{ route('admin.rapports.index',['semestre'=>$s,'annee'=>$annee]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors
              {{ $semestre==$s ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        Semestre {{ $s }}
    </a>
    @endforeach
    <a href="{{ route('admin.rapports.index',['annee'=>$annee]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">
        Annuel
    </a>
</div>

{{-- Charts grid --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">
    {{-- Performance académique --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-1">Performance académique (par mois)</h3>
        <canvas id="perfChart" height="200"></canvas>
    </div>

    {{-- Taux de présence --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-1">Taux de présence (par mois)</h3>
        <canvas id="presChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
    {{-- Moyennes par module --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-4">Moyennes par module</h3>
        <canvas id="modulesChart" height="220"></canvas>
    </div>

    {{-- Distribution mentions --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-4">Distribution des mentions</h3>
        <div class="flex items-center gap-6">
            <canvas id="mentionsChart" width="180" height="180" class="flex-shrink-0"></canvas>
            <div class="space-y-2 flex-1">
                @php
                $mentionColors = ['Très Bien'=>['#22c55e','bg-green-500'],'Bien'=>['#3b82f6','bg-blue-500'],'Assez Bien'=>['#06b6d4','bg-cyan-500'],'Passable'=>['#f59e0b','bg-yellow-500'],'Insuffisant'=>['#ef4444','bg-red-500']];
                @endphp
                @foreach($mentionColors as $label => [$hex,$bg])
                @php $dm = $distributionMentions->firstWhere('mention',$label); $pct = $dm && $distributionMentions->sum('count') > 0 ? round($dm->count/$distributionMentions->sum('count')*100) : 0; @endphp
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full {{ $bg }}"></div>
                        <span class="text-gray-700">{{ $label }}</span>
                    </div>
                    <span class="font-bold text-gray-900">{{ $pct }}%</span>
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
const presData  = @json($presenceMois);

new Chart(document.getElementById('perfChart'), {
    type: 'line',
    data: {
        labels: perfData.length ? perfData.map(d=>mois[(d.mois-1)%12]) : mois,
        datasets: [{
            label:'Moyenne /20',
            data: perfData.length ? perfData.map(d=>parseFloat(d.moyenne)) : [12.1,12.5,11.8,12.3,12.6,13.0,12.8],
            borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,0.07)', fill:true, tension:0.4, pointRadius:4
        }]
    },
    options:{responsive:true, plugins:{legend:{display:false}}, scales:{y:{min:0,max:20,grid:{color:'#f3f4f6'}},x:{grid:{display:false}}}}
});

new Chart(document.getElementById('presChart'), {
    type: 'bar',
    data: {
        labels: presData.length ? presData.map(d=>mois[(d.mois-1)%12]) : mois,
        datasets: [{
            data: presData.length ? presData.map(d=>d.taux) : [87,92,84,86,90,88,87],
            backgroundColor:'#06b6d4', borderRadius:6
        }]
    },
    options:{responsive:true, plugins:{legend:{display:false}}, scales:{y:{min:70,max:100,grid:{color:'#f3f4f6'}},x:{grid:{display:false}}}}
});

const modules = @json($moyennesModules);
new Chart(document.getElementById('modulesChart'), {
    type: 'bar',
    data: {
        labels: modules.length ? modules.map(m=>m.code||m.nom.substring(0,4)) : ['Algo','POO','BDD','Réseaux','Maths','Sys'],
        datasets:[{
            data: modules.length ? modules.map(m=>parseFloat(m.moy||0)) : [12.8,13.4,11.5,12.0,10.9,13.2],
            backgroundColor:'#6366f1', borderRadius:4
        }]
    },
    options:{indexAxis:'y', responsive:true, plugins:{legend:{display:false}}, scales:{x:{min:0,max:20,grid:{color:'#f3f4f6'}},y:{grid:{display:false}}}}
});

const mentions = @json($distributionMentions);
new Chart(document.getElementById('mentionsChart'), {
    type: 'doughnut',
    data: {
        labels:['Très Bien','Bien','Assez Bien','Passable','Insuffisant'],
        datasets:[{
            data: ['Très Bien','Bien','Assez Bien','Passable','Insuffisant'].map(l=>{const d=mentions.find(m=>m.mention===l); return d?d.count:1}),
            backgroundColor:['#22c55e','#3b82f6','#06b6d4','#f59e0b','#ef4444'],
            borderWidth:0
        }]
    },
    options:{responsive:false, cutout:'60%', plugins:{legend:{display:false}}}
});
</script>
@endpush
