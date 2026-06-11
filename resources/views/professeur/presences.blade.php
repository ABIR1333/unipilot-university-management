@extends('layouts.professeur')
@section('title','Suivi des présences')
@section('page-title','Suivi des présences')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Suivi des présences</h1>
    <p class="text-sm text-gray-500 mt-1">Gérez les présences de vos étudiants par séance</p>
</div>

<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-3">
        <div class="relative">
            <form method="GET" id="filterForm">
                <select name="seance_id" onchange="this.form.submit()"
                        class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2 pr-10 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($seances as $seance)
                    <option value="{{ $seance['value'] }}" {{ $selectedSeanceId==$seance['value']?'selected':'' }}>
                        {{ $seance['label'] }}
                    </option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                <input type="date" name="date" value="{{ $date }}"
                       onchange="this.form.submit()"
                       class="hidden">
                <input type="hidden" name="seance_id" value="{{ $selectedSeanceId }}">
            </form>
        </div>
        <div class="relative">
            <input type="date" name="date" value="{{ $date }}"
                   onchange="document.getElementById('filterForm').submit()"
                   class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
    @if(isset($selectedSeance) && $selectedSeance)
    <button form="presenceForm" type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fa-solid fa-floppy-disk text-xs"></i> Valider
    </button>
    @endif
</div>

@if(isset($selectedSeance) && $selectedSeance)
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 text-center">
        <div class="w-10 h-10 rounded-full bg-green-200 flex items-center justify-center mx-auto mb-2">
            <i class="fa-solid fa-user-check text-green-600 text-sm"></i>
        </div>
        <p class="text-2xl font-bold text-green-600">{{ $statsToday['presents'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Présents</p>
    </div>
    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-4 text-center">
        <div class="w-10 h-10 rounded-full bg-red-200 flex items-center justify-center mx-auto mb-2">
            <i class="fa-solid fa-user-xmark text-red-600 text-sm"></i>
        </div>
        <p class="text-2xl font-bold text-red-600">{{ $statsToday['absents'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Absents</p>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 text-center">
        <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center mx-auto mb-2">
            <i class="fa-solid fa-chart-line text-blue-600 text-sm"></i>
        </div>
        <p class="text-2xl font-bold text-blue-600">{{ $statsToday['taux'] }}%</p>
        <p class="text-xs text-gray-500 mt-0.5">Taux présence</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <form id="presenceForm" method="POST" action="{{ route('admin.professeur.presences.store') }}">
        @csrf
        <input type="hidden" name="module_id" value="{{ $selectedSeance->module_id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="px-4 py-2.5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-blue-500 text-sm"></i>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    FEUILLE DE PRÉSENCE — {{ strtoupper($selectedSeance->module->nom) }}
                </p>
            </div>
            <div x-data="{}">
                <button type="button"
                        @click="document.querySelectorAll('[data-presence]').forEach(r=>r.value='Présent'); document.querySelectorAll('[x-data]').forEach(el=>el.__x.$data.statut='Présent')"
                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fa-solid fa-check text-xs"></i> Tous présents
                </button>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($presences as $i => $item)
            <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors" 
                 x-data="{ statut: '{{ $item['statut'] }}' }">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ $item['etudiant']->initials }}
                    </div>
                    <span class="font-medium text-gray-800 text-sm">{{ $item['etudiant']->nom }}</span>
                </div>
                <input type="hidden" name="presences[{{ $i }}][etudiant_id]" value="{{ $item['etudiant']->id }}">
                <input type="hidden" name="presences[{{ $i }}][statut]" :value="statut" data-presence>

                <div class="flex gap-1.5">
                    <button type="button"
                            @click="statut='Présent'"
                            :class="statut==='Présent' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-gray-50 text-gray-400 border-gray-200'"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg border text-xs font-medium transition-all">
                        <i class="fa-solid fa-check text-xs"></i> Présent
                    </button>
                    <button type="button"
                            @click="statut='Absent'"
                            :class="statut==='Absent' ? 'bg-red-100 text-red-700 border-red-300' : 'bg-gray-50 text-gray-400 border-gray-200'"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg border text-xs font-medium transition-all">
                        <i class="fa-solid fa-xmark text-xs"></i> Absent
                    </button>
                    <button type="button"
                            @click="statut='Justifié'"
                            :class="statut==='Justifié' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : 'bg-gray-50 text-gray-400 border-gray-200'"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg border text-xs font-medium transition-all">
                        <i class="fa-solid fa-exclamation text-xs"></i> Justifié
                    </button>
                </div>
            </div>
            @empty
            <div class="px-4 py-10 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-users-slash text-2xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm">Aucun étudiant inscrit</p>
                <p class="text-xs text-gray-400 mt-1">Ce module n'a pas d'étudiants inscrits</p>
            </div>
            @endforelse
        </div>
    </form>
</div>
@endif

@push('scripts')
<script>
function updateStats() {
    // Optional: update stats dynamically
}
</script>
@endpush
@endsection