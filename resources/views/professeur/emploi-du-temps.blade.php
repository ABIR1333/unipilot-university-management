@extends('layouts.professeur')
@section('title','Mon emploi du temps')
@section('page-title','Mon emploi du temps')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mon emploi du temps</h1>
    <p class="text-sm text-gray-500 mt-1">Consultez vos cours et séances planifiées</p>
</div>

@php
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
@endphp

@if(isset($emploiSemaine) && !empty($emploiSemaine))
<div class="grid grid-cols-1 md:grid-cols-5 gap-4">
    @foreach($jours as $jour)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700">
            <h3 class="font-semibold text-white text-sm text-center">{{ $jour }}</h3>
        </div>
        <div class="p-2 space-y-2 min-h-[380px]">
            @php 
                $seancesJour = isset($emploiSemaine[$jour]) ? $emploiSemaine[$jour] : collect();
            @endphp
            @if($seancesJour->isEmpty())
            <div class="flex flex-col items-center justify-center py-6">
                <i class="fa-regular fa-calendar-xmark text-2xl text-gray-300 mb-1"></i>
                <span class="text-xs text-gray-400">Aucun cours</span>
            </div>
            @else
            @foreach($seancesJour as $seance)
            <div class="rounded-lg p-2 transition-all hover:scale-[1.01] {{ $seance->type_seance == 'CM' ? 'bg-blue-50 border-l-3 border-blue-500' : ($seance->type_seance == 'TD' ? 'bg-purple-50 border-l-3 border-purple-500' : 'bg-green-50 border-l-3 border-green-500') }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-xs {{ $seance->type_seance == 'CM' ? 'text-blue-800' : ($seance->type_seance == 'TD' ? 'text-purple-800' : 'text-green-800') }}">
                            {{ $seance->module->nom ?? 'Module' }}
                        </p>
                        <div class="flex items-center gap-1 mt-1">
                            <i class="fa-regular fa-clock text-xs text-gray-500"></i>
                            <p class="text-xs text-gray-600">
                                {{ substr($seance->heure_debut, 0, 5) }} - {{ substr($seance->heure_fin, 0, 5) }}
                            </p>
                        </div>
                        @if($seance->salle)
                        <div class="flex items-center gap-1 mt-0.5">
                            <i class="fa-solid fa-location-dot text-xs text-gray-500"></i>
                            <p class="text-xs text-gray-500">{{ $seance->salle->nom ?? 'Salle' }}</p>
                        </div>
                        @endif
                    </div>
                    <span class="text-xs font-bold px-1.5 py-0.5 rounded-full {{ $seance->type_seance == 'CM' ? 'bg-blue-200 text-blue-700' : ($seance->type_seance == 'TD' ? 'bg-purple-200 text-purple-700' : 'bg-green-200 text-green-700') }}">
                        {{ $seance->type_seance ?? 'CM' }}
                    </span>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
    <i class="fa-regular fa-calendar text-4xl text-gray-300 mb-3"></i>
    <h3 class="text-md font-semibold text-gray-700 mb-1">Aucun cours trouvé</h3>
    <p class="text-sm text-gray-400">Votre emploi du temps est vide pour le moment.</p>
</div>
@endif
@endsection