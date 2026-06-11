@extends('layouts.professeur')
@section('title', 'Saisie des notes')
@section('page-title', 'Saisie des notes')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Saisie des notes</h1>
    <p class="text-sm text-gray-500 mt-1">Saisissez et gérez les notes de vos étudiants</p>
</div>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <div class="relative">
            <form method="GET">
                <select name="module_id" onchange="this.form.submit()"
                        class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($modules as $m)
                    <option value="{{ $m->id }}" {{ $selectedModule?->id==$m->id?'selected':'' }}>
                        {{ $m->nom }} — {{ $m->programme->code }} S{{ $m->semestre_type==='Impair'?'4':'3' }}
                    </option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </form>
        </div>
        @if($selectedModule)
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
            <span class="text-sm text-gray-600">Saisie des notes — Examen final</span>
        </div>
        @endif
    </div>
    @if($selectedModule)
    <div class="flex gap-2">
        <a href="{{ route('admin.professeur.notes.export-pdf', ['module_id' => $selectedModule->id, 'annee' => '2024-2025']) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">
            <i class="fa-solid fa-download text-xs"></i> PDF
        </a>
        <button form="notesForm" type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-solid fa-floppy-disk text-xs"></i> Enregistrer
        </button>
    </div>
    @endif
</div>

@if($selectedModule)
<div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg px-4 py-2.5 mb-5">
    <div class="flex items-center gap-2">
        <i class="fa-solid fa-calculator text-blue-600 text-sm"></i>
        <p class="text-sm text-blue-700 font-medium">Note finale = CC1 (25%) + CC2 (25%) + Examen final (50%)</p>
    </div>
</div>

@if(count($statsClasse) > 0)
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-lg border border-gray-200 p-3 text-center shadow-sm">
        <p class="text-xs text-gray-500 mb-0.5">Moyenne</p>
        <p class="text-lg font-bold text-gray-900">{{ $statsClasse['moyenne'] }}/20</p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-3 text-center shadow-sm">
        <p class="text-xs text-gray-500 mb-0.5">Taux réussite</p>
        <p class="text-lg font-bold text-green-600">{{ $statsClasse['taux_reussite'] }}%</p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-3 text-center shadow-sm">
        <p class="text-xs text-gray-500 mb-0.5">Note max</p>
        <p class="text-lg font-bold text-blue-600">{{ $statsClasse['note_max'] }}/20</p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-3 text-center shadow-sm">
        <p class="text-xs text-gray-500 mb-0.5">Note min</p>
        <p class="text-lg font-bold text-red-600">{{ $statsClasse['note_min'] }}/20</p>
    </div>
</div>
@endif

{{-- Notes table --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <form id="notesForm" method="POST" action="{{ route('admin.professeur.notes.save') }}">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Étudiant</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">CC1 /20</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">CC2 /20</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Examen /20</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Note finale</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Mention</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notes as $i => $item)
                    @php $note = $item['note']; $etudiant = $item['etudiant']; @endphp
                    <input type="hidden" name="notes[{{ $i }}][etudiant_id]" value="{{ $etudiant->id }}">
                    <input type="hidden" name="notes[{{ $i }}][module_id]" value="{{ $selectedModule->id }}">
                    <input type="hidden" name="notes[{{ $i }}][annee_academique]" value="2024-2025">
                    <input type="hidden" name="notes[{{ $i }}][semestre]" value="4">
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                                    {{ strtoupper(substr($etudiant->nom, 0, 1)) }}
                                </div>
                                <p class="font-medium text-gray-900 text-sm">{{ $etudiant->nom }}</p>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if($note?->note_cc1 !== null)
                            <span class="text-gray-700 font-medium text-sm">{{ number_format($note->note_cc1,0) }}</span>
                            @else <span class="text-gray-300 text-sm">—</span>
                            @endif
                            <input type="hidden" name="notes[{{ $i }}][note_cc1]" value="{{ $note?->note_cc1 }}">
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if($note?->note_cc2 !== null)
                            <span class="text-gray-700 font-medium text-sm">{{ number_format($note->note_cc2,0) }}</span>
                            @else <span class="text-gray-300 text-sm">—</span>
                            @endif
                            <input type="hidden" name="notes[{{ $i }}][note_cc2]" value="{{ $note?->note_cc2 }}">
                        </td>
                        <td class="px-3 py-3 text-center">
                            <input type="number" name="notes[{{ $i }}][note_examen]"
                                   value="{{ old("notes.{$i}.note_examen", $note?->note_examen) }}"
                                   min="0" max="20" step="0.5" placeholder="—"
                                   class="w-20 px-2 py-1.5 text-center border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 hover:bg-white transition">
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if($note?->note_finale !== null)
                            <span class="text-sm font-bold {{ $note->note_finale_color }}">{{ number_format($note->note_finale,1) }}</span>
                            @else <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if($note?->mention)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $note->mention_color }}">{{ $note->mention }}</span>
                            @else <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-users-slash text-2xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500">Aucun étudiant inscrit</p>
                            <p class="text-sm text-gray-400 mt-1">Aucun étudiant n'est inscrit à ce module</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>
@endif
@endsection