@extends('layouts.app')
@section('title','Présences')
@section('page-title','Suivi des présences')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Suivi des présences</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
    @if($selectedModule)
    <a href="{{ route('admin.presences.export-pdf',['module_id'=>$selectedModule->id]) }}" class="btn-secondary">
        <i class="fa-solid fa-download"></i> Exporter
    </a>
    @endif
</div>

{{-- Filters --}}
<div class="flex flex-wrap gap-3 mb-5">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <select name="module_id" onchange="this.form.submit()" class="form-input w-auto">
            @foreach($modules as $m)
            <option value="{{ $m->id }}" {{ $selectedModule?->id==$m->id?'selected':'' }}>{{ $m->nom }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ $date }}" class="form-input w-auto"
               onchange="this.form.submit()">
        @if($selectedModule)
        <input type="hidden" name="module_id" value="{{ $selectedModule->id }}">
        @endif
    </form>
</div>

@if($selectedModule && count($statsGlobales) > 0)
{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $statsGlobales['presents_today'] }}</p>
        <p class="text-sm text-gray-500">Présents aujourd'hui</p>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-red-600">{{ $statsGlobales['absents_today'] }}</p>
        <p class="text-sm text-gray-500">Absents</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ $statsGlobales['justifies_today'] }}</p>
        <p class="text-sm text-gray-500">Justifiés</p>
    </div>
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $statsGlobales['taux_global'] }}%</p>
        <p class="text-sm text-gray-500">Taux global</p>
    </div>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr>
                <th>ÉTUDIANT</th>
                <th class="text-center">PRÉSENCES</th>
                <th class="text-center">ABSENCES</th>
                <th class="text-center">JUSTIFIÉES</th>
                <th>TAUX</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($presences as $p)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                            {{ $p['etudiant']->initials }}
                        </div>
                        <span class="font-medium text-gray-900 text-sm">{{ $p['etudiant']->nom }}</span>
                    </div>
                </td>
                <td class="text-center font-bold text-green-600">{{ $p['presents'] }}</td>
                <td class="text-center font-bold text-red-600">{{ $p['absents'] }}</td>
                <td class="text-center font-bold text-yellow-600">{{ $p['justifies'] }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-indigo-600" style="width:{{ $p['taux'] }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 w-10">{{ $p['taux'] }}%</span>
                        @if($p['alerte'])
                        <span class="text-yellow-500 text-xs"><i class="fa-solid fa-triangle-exclamation"></i> Alerte</span>
                        @endif
                    </div>
                </td>
                <td>
                    {{-- Quick attendance form for today --}}
                    <div x-data="{open:false}" class="relative">
                        <button @click="open=!open" class="btn-secondary btn-sm">
                            <i class="fa-solid fa-plus"></i> Saisir
                        </button>
                        <div x-show="open" x-cloak @click.away="open=false"
                             class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-gray-200 p-4 z-10">
                            <form method="POST" action="{{ route('admin.presences.store') }}">
                                @csrf
                                <input type="hidden" name="module_id" value="{{ $selectedModule->id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <input type="hidden" name="presences[0][etudiant_id]" value="{{ $p['etudiant']->id }}">
                                <p class="font-semibold text-sm mb-2">{{ $p['etudiant']->nom }}</p>
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    @foreach(['Présent','Absent','Justifié'] as $st)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="presences[0][statut]" value="{{ $st }}" class="sr-only peer">
                                        <div class="text-center text-xs py-1.5 rounded-lg border-2 border-gray-200
                                                    peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                                                    hover:border-gray-300 transition-all">{{ $st }}</div>
                                    </label>
                                    @endforeach
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" @click="open=false" class="btn-secondary flex-1 text-xs py-1">Annuler</button>
                                    <button type="submit" class="btn-primary flex-1 text-xs py-1">OK</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-gray-400">Aucun étudiant inscrit à ce module</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection
