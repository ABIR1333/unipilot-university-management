@extends('layouts.etudiant')

@section('title', 'Mon emploi du temps')
@section('page-title', 'Mon emploi du temps')

@section('content')
<style>
    .day-card { transition: all 0.2s; }
    .day-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .cours-item { transition: all 0.15s; }
    .cours-item:hover { transform: translateX(2px); }
    .badge-cm { background: #dbeafe; color: #1e40af; }
    .badge-td { background: #f3e8ff; color: #6b21a5; }
    .badge-tp { background: #dcfce7; color: #166534; }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mon emploi du temps</h1>
    <p class="text-xs text-gray-500 mt-0.5">Semaine du {{ now()->startOfWeek()->format('d/m') }} au {{ now()->endOfWeek()->format('d/m/Y') }}</p>
</div>

<div class="grid grid-cols-5 gap-3">
    @php
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        $coursParJour = [
            'Lundi' => [
                ['nom' => 'Algorithmique', 'heure_debut' => '08:00', 'heure_fin' => '10:00', 'salle' => 'Amphi A1', 'professeur' => 'Dr. Dubois', 'type' => 'CM'],
                ['nom' => 'POO Java', 'heure_debut' => '10:15', 'heure_fin' => '12:15', 'salle' => 'Salle 201', 'professeur' => 'Prof. Martin', 'type' => 'TD'],
                ['nom' => 'Base de données', 'heure_debut' => '14:00', 'heure_fin' => '16:00', 'salle' => 'Labo L3', 'professeur' => 'Dr. Leclerc', 'type' => 'TP']
            ],
            'Mardi' => [
                ['nom' => 'Algorithmique', 'heure_debut' => '08:00', 'heure_fin' => '10:00', 'salle' => 'Amphi B2', 'professeur' => 'Dr. Benali', 'type' => 'CM'],
                ['nom' => 'Réseaux', 'heure_debut' => '10:15', 'heure_fin' => '12:15', 'salle' => 'Salle 105', 'professeur' => 'Dr. Moreau', 'type' => 'TD'],
                ['nom' => 'Mathématiques', 'heure_debut' => '14:00', 'heure_fin' => '16:00', 'salle' => 'Amphi C', 'professeur' => 'Prof. Bernard', 'type' => 'CM']
            ],
            'Mercredi' => [
                ['nom' => 'POO Java', 'heure_debut' => '08:00', 'heure_fin' => '10:00', 'salle' => 'Labo L1', 'professeur' => 'Prof. Martin', 'type' => 'TP'],
                ['nom' => 'Base de données', 'heure_debut' => '10:15', 'heure_fin' => '12:15', 'salle' => 'Salle 302', 'professeur' => 'Dr. Leclerc', 'type' => 'TD'],
                ['nom' => 'Systèmes', 'heure_debut' => '14:00', 'heure_fin' => '16:00', 'salle' => 'Labo L2', 'professeur' => 'Dr. Petit', 'type' => 'TP']
            ],
            'Jeudi' => [
                ['nom' => 'Réseaux', 'heure_debut' => '09:00', 'heure_fin' => '11:00', 'salle' => 'Amphi A1', 'professeur' => 'Dr. Moreau', 'type' => 'CM'],
                ['nom' => 'Mathématiques', 'heure_debut' => '11:15', 'heure_fin' => '13:15', 'salle' => 'Salle 201', 'professeur' => 'Prof. Bernard', 'type' => 'TD'],
                ['nom' => 'Algorithmique', 'heure_debut' => '14:00', 'heure_fin' => '16:00', 'salle' => 'Labo L1', 'professeur' => 'Dr. Benali', 'type' => 'TP']
            ],
            'Vendredi' => [
                ['nom' => 'Systèmes', 'heure_debut' => '08:00', 'heure_fin' => '10:00', 'salle' => 'Amphi B2', 'professeur' => 'Dr. Petit', 'type' => 'CM'],
                ['nom' => 'POO Java', 'heure_debut' => '10:15', 'heure_fin' => '12:15', 'salle' => 'Salle 105', 'professeur' => 'Prof. Martin', 'type' => 'TD'],
                ['nom' => 'Base de données', 'heure_debut' => '13:30', 'heure_fin' => '15:30', 'salle' => 'Labo L3', 'professeur' => 'Dr. Leclerc', 'type' => 'TP']
            ]
        ];
    @endphp

    @foreach($jours as $jour)
    <div class="day-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-3 py-2 text-center">
            <p class="text-white font-semibold text-sm">{{ strtoupper(substr($jour, 0, 3)) }}</p>
        </div>
        <div class="p-2 space-y-2 min-h-[480px]">
            @forelse($coursParJour[$jour] ?? [] as $cours)
            <div class="cours-item p-2 rounded-lg bg-gray-50 border-l-3 
                {{ $cours['type'] == 'CM' ? 'border-blue-500' : ($cours['type'] == 'TD' ? 'border-purple-500' : 'border-green-500') }}">
                <p class="font-semibold text-gray-800 text-xs">{{ $cours['nom'] }}</p>
                <p class="text-[10px] text-gray-500 mt-0.5">{{ $cours['heure_debut'] }}–{{ $cours['heure_fin'] }}</p>
                <p class="text-[10px] text-gray-400">{{ $cours['salle'] }}</p>
                <p class="text-[10px] text-gray-400">{{ $cours['professeur'] }}</p>
                <span class="inline-block mt-1.5 px-1.5 py-0.5 rounded-full text-[9px] font-semibold 
                    {{ $cours['type'] == 'CM' ? 'badge-cm' : ($cours['type'] == 'TD' ? 'badge-td' : 'badge-tp') }}">
                    {{ $cours['type'] }}
                </span>
            </div>
            @empty
            <div class="text-center py-6 text-gray-400 text-[11px]">Libre</div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6 flex justify-between items-center p-3 bg-gray-50 rounded-xl">
    <div class="flex items-center gap-4 text-[11px]">
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span><span class="text-gray-600">CM</span></div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-purple-500"></span><span class="text-gray-600">TD</span></div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500"></span><span class="text-gray-600">TP</span></div>
    </div>
    <div class="text-[10px] text-gray-400">
        <i class="fa-regular fa-clock mr-1"></i> Mis à jour le {{ now()->format('d/m/Y') }}
    </div>
</div>
@endsection