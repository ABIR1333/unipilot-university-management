@extends('layouts.app')
@section('title','Notes')
@section('page-title','Gestion des notes')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Gestion des notes</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
    @if($selectedModule)
    <div class="flex gap-2">
        <a href="{{ route('admin.notes.export-pdf',['module_id'=>$selectedModule->id,'annee'=>$annee]) }}"
           class="btn-secondary"><i class="fa-solid fa-download"></i> Exporter</a>
    </div>
    @endif
</div>

{{-- Module selector --}}
<div class="flex flex-wrap items-center gap-3 mb-5">
    <form method="GET" class="flex items-center gap-3">
        <div class="relative">
            <select name="module_id" onchange="this.form.submit()" class="form-input pr-8 appearance-none w-56">
                @foreach($modules as $m)
                <option value="{{ $m->id }}" {{ $selectedModule?->id==$m->id?'selected':'' }}>{{ $m->nom }}</option>
                @endforeach
            </select>
        </div>
        @if($selectedModule)
        <span class="text-sm text-gray-500">Semestre {{ $selectedModule->semestre_type === 'Impair' ? '4' : '3' }} · {{ $selectedModule->programme->nom }}</span>
        @endif
        <input type="hidden" name="annee" value="{{ $annee }}">
    </form>
</div>

@if($selectedModule)
{{-- Stats --}}
@if(count($statsClasse) > 0)
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-gray-50 rounded-xl p-3 text-center">
        <p class="text-sm text-gray-500">Moyenne de la classe</p>
        <p class="text-lg font-bold text-gray-900">{{ $statsClasse['moyenne'] }}/20</p>
    </div>
    <div class="bg-gray-50 rounded-xl p-3 text-center">
        <p class="text-sm text-gray-500">Taux de réussite</p>
        <p class="text-lg font-bold text-green-600">{{ $statsClasse['taux_reussite'] }}%</p>
    </div>
    <div class="bg-gray-50 rounded-xl p-3 text-center">
        <p class="text-sm text-gray-500">Note la plus haute</p>
        <p class="text-lg font-bold text-blue-600">{{ $statsClasse['note_max'] }}</p>
    </div>
    <div class="bg-gray-50 rounded-xl p-3 text-center">
        <p class="text-sm text-gray-500">Note la plus basse</p>
        <p class="text-lg font-bold text-red-600">{{ $statsClasse['note_min'] }}</p>
    </div>
</div>
@endif

{{-- Notes table --}}
<div class="card overflow-hidden">
    <form method="POST" action="{{ route('admin.notes.bulk') }}">
        @csrf
        <table class="w-full">
            <thead>
                <tr>
                    <th>ÉTUDIANT</th>
                    <th class="text-center">CC1 /20</th>
                    <th class="text-center">CC2 /20</th>
                    <th class="text-center">EXAMEN /20</th>
                    <th class="text-center">NOTE FINALE</th>
                    <th class="text-center">MENTION</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $i => $note)
                <input type="hidden" name="notes[{{ $i }}][note_id]" value="{{ $note->id }}">
                <input type="hidden" name="notes[{{ $i }}][etudiant_id]" value="{{ $note->etudiant_id }}">
                <input type="hidden" name="notes[{{ $i }}][module_id]" value="{{ $note->module_id }}">
                <input type="hidden" name="notes[{{ $i }}][annee_academique]" value="{{ $annee }}">
                <input type="hidden" name="notes[{{ $i }}][semestre]" value="{{ $note->semestre }}">
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ $note->etudiant->initials }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $note->etudiant->nom }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <input type="number" name="notes[{{ $i }}][note_cc1]" value="{{ old("notes.{$i}.note_cc1",$note->note_cc1) }}"
                               min="0" max="20" step="0.5"
                               class="w-16 px-2 py-1 text-center text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </td>
                    <td class="text-center">
                        <input type="number" name="notes[{{ $i }}][note_cc2]" value="{{ old("notes.{$i}.note_cc2",$note->note_cc2) }}"
                               min="0" max="20" step="0.5"
                               class="w-16 px-2 py-1 text-center text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </td>
                    <td class="text-center">
                        <input type="number" name="notes[{{ $i }}][note_examen]" value="{{ old("notes.{$i}.note_examen",$note->note_examen) }}"
                               min="0" max="20" step="0.5"
                               class="w-16 px-2 py-1 text-center text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </td>
                    <td class="text-center {{ $note->note_finale_color }} font-bold">
                        {{ $note->note_finale !== null ? number_format($note->note_finale,1) : '—' }}
                    </td>
                    <td class="text-center">
                        @if($note->mention)
                        <span class="badge {{ $note->mention_color }}">{{ $note->mention }}</span>
                        @else <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" onclick="this.closest('tr').querySelector('form').submit()"
                                class="p-1.5 text-gray-400 hover:text-indigo-600 rounded transition-colors" title="Sauvegarder">
                            <i class="fa-solid fa-pen text-sm"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-gray-400">Aucune note pour ce module</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($notes->count() > 0)
        <div class="px-5 py-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Sauvegarder toutes les notes
            </button>
        </div>
        @endif
    </form>
</div>
@endif
@endsection
