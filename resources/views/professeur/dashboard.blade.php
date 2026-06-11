@extends('layouts.professeur')
@section('title','Tableau de bord')
@section('page-title','Tableau de bord')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
    <p class="text-sm text-gray-500 mt-1">Bienvenue, {{ auth()->user()->name }}</p>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Cours aujourd'hui --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-4 text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
            <i class="fa-solid fa-calendar-day text-xl text-blue-200"></i>
        </div>
        <p class="text-blue-100 text-xs uppercase tracking-wide mb-1">Cours aujourd'hui</p>
        <p class="text-3xl font-bold mb-0.5">{{ $coursAujourdhui->count() }}</p>
        <p class="text-blue-100 text-xs">{{ now()->isoFormat('dddd D MMM') }}</p>
    </div>

    {{-- Modules assignés --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-2">
            <i class="fa-solid fa-book-open text-xl text-blue-500"></i>
        </div>
        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Modules assignés</p>
        <p class="text-3xl font-bold text-gray-900 mb-0.5">{{ $modules->count() }}</p>
        <p class="text-gray-400 text-xs">Semestre 4 · {{ $modules->first()?->programme->code ?? 'L3 Info' }}</p>
    </div>

    {{-- Étudiants encadrés --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-2">
            <i class="fa-solid fa-users text-xl text-green-500"></i>
        </div>
        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Étudiants encadrés</p>
        <p class="text-3xl font-bold text-gray-900 mb-0.5">{{ $totalEtudiants }}</p>
        <p class="text-gray-400 text-xs">{{ $modules->count() }} groupes actifs</p>
    </div>

    {{-- Notes à saisir --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-2">
            <i class="fa-solid fa-pen-fancy text-xl text-orange-500"></i>
        </div>
        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Notes à saisir</p>
        <p class="text-3xl font-bold text-orange-500 mb-0.5">{{ $notesASaisir }}</p>
        <p class="text-gray-400 text-xs">Examen final — {{ $moduleNotesASaisir['module']->nom ?? ($modules->first()->nom ?? 'Algo') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    {{-- Planning semaine --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="font-semibold text-gray-900 text-sm">Mes cours</h3>
            <p class="text-xs text-gray-400 mt-0.5">Semaine du {{ now()->startOfWeek()->format('j') }} au {{ now()->endOfWeek()->format('j M Y') }}</p>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-5 gap-2">
                @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi'] as $jour)
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase text-center mb-2">
                        {{ strtoupper(substr($jour,0,3)) }}
                    </p>
                    <div class="space-y-2">
                        @php $seancesJour = $emploiSemaine->get($jour, collect()); @endphp
                        @if($seancesJour->isEmpty())
                        <div class="text-center py-3 bg-gray-50 rounded-lg">
                            <span class="text-xs text-gray-400">Libre</span>
                        </div>
                        @else
                        @foreach($seancesJour as $seance)
                        <div class="rounded-lg p-2 {{ $seance->type_seance == 'CM' ? 'bg-blue-50 border-l-3 border-blue-500' : ($seance->type_seance == 'TD' ? 'bg-purple-50 border-l-3 border-purple-500' : 'bg-green-50 border-l-3 border-green-500') }}">
                            <p class="font-semibold text-xs">{{ Str::limit($seance->module->nom, 20) }}</p>
                            <p class="text-xs text-gray-600 mt-0.5">
                                {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                            </p>
                            @if($seance->salle)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $seance->salle->nom }}</p>
                            @endif
                            <span class="inline-block mt-1.5 px-1.5 py-0.5 rounded-full text-xs font-semibold {{ $seance->type_seance == 'CM' ? 'bg-blue-200 text-blue-700' : ($seance->type_seance == 'TD' ? 'bg-purple-200 text-purple-700' : 'bg-green-200 text-green-700') }}">
                                {{ $seance->type_seance }}
                            </span>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="space-y-4">
        {{-- Mes modules --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-900 text-sm">Mes modules</h3>
            </div>
            <div class="p-4 space-y-3">
                @foreach($modules as $module)
                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                    <p class="font-semibold text-gray-900 text-sm">{{ Str::limit($module->nom, 35) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $module->code ?? 'INFO' }} · {{ $module->heures }}h
                    </p>
                    @php
                        $nb = \App\Models\Inscription::where('module_id',$module->id)->distinct('etudiant_id')->count('etudiant_id');
                    @endphp
                    <p class="text-xs text-gray-400 mt-0.5">{{ $nb }} étudiants</p>
                </div>
                @endforeach
                @if($modules->isEmpty())
                <p class="text-sm text-gray-400 text-center py-3">Aucun module assigné</p>
                @endif
            </div>
        </div>

        {{-- À faire --}}
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-4">
            <div class="flex items-center gap-2 mb-3">
                <i class="fa-regular fa-clock text-orange-500 text-sm"></i>
                <h3 class="font-semibold text-gray-900 text-sm">À faire</h3>
            </div>
            <ul class="space-y-2">
                @foreach($aFaire as $item)
                <li class="flex items-start gap-2 text-sm">
                    <div class="w-1.5 h-1.5 rounded-full mt-1.5 {{ $item['priorite'] === 'urgent' ? 'bg-red-500' : 'bg-blue-500' }}"></div>
                    <span class="text-xs {{ $item['priorite'] === 'urgent' ? 'text-red-700 font-medium' : 'text-gray-700' }}">
                        {{ $item['texte'] }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection