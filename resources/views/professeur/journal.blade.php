@extends('layouts.professeur')

@section('title', 'Journal pédagogique')
@section('page-title', 'Journal pédagogique')

@section('content')
<style>
    .seance-card {
        transition: all 0.2s ease;
    }
    .seance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
</style>

<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Journal pédagogique</h1>
            <p class="text-sm text-gray-500 mt-1">Suivez et gérez vos séances de cours</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <select name="module_id" onchange="this.form.submit()" 
                        class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($modules ?? [] as $m)
                    <option value="{{ $m->id }}" {{ ($selectedModule?->id ?? ($modules->first()->id ?? null)) == $m->id ? 'selected' : '' }}>
                        {{ $m->nom }}
                    </option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <button onclick="openModal()" 
                    class="inline-flex items-center gap-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all">
                <i class="fa-solid fa-plus text-xs"></i> Nouvelle séance
            </button>
        </div>
    </div>
</div>

<div class="space-y-4">
    @forelse($entrees ?? [] as $entree)
    <div class="seance-card group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
        <div class="p-5">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $entree->type_seance == 'CM' ? 'bg-blue-100 text-blue-700' : ($entree->type_seance == 'TD' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700') }}">
                            <i class="fa-solid {{ $entree->type_seance == 'CM' ? 'fa-chalkboard-user' : ($entree->type_seance == 'TD' ? 'fa-people-group' : 'fa-flask') }} text-xs"></i>
                            {{ $entree->type_seance }}
                        </span>
                        <span class="text-xs text-gray-400">
                            <i class="fa-regular fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($entree->date)->format('d/m/Y') }}
                        </span>
                        <span class="text-xs text-gray-400">
                            <i class="fa-regular fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($entree->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($entree->heure_fin)->format('H:i') }}
                        </span>
                        @if($entree->salle)
                        <span class="text-xs text-gray-400">
                            <i class="fa-solid fa-location-dot mr-1"></i>
                            {{ $entree->salle }}
                        </span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 text-base">{{ $entree->titre }}</h3>
                    @if($entree->notes)
                    <div class="mt-3 p-3 bg-blue-50/30 rounded-lg border-l-4 border-blue-400">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <i class="fa-regular fa-note-sticky mr-1.5 text-blue-400"></i>
                            {{ $entree->notes }}
                        </p>
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-1 self-start">
                    <button onclick="editSeance({{ $entree->id }})" 
                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                        <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.professeur.journal.destroy', $entree) }}"
                          onsubmit="return confirm('Supprimer cette séance ?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-book-open text-3xl text-gray-400"></i>
        </div>
        <p class="text-gray-500 font-medium">Aucune séance dans le journal</p>
        <p class="text-sm text-gray-400 mt-1">Cliquez sur "Nouvelle séance" pour commencer</p>
        <button onclick="openModal()" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium mt-4 transition-all">
            <i class="fa-solid fa-plus text-xs"></i> Ajouter la première séance
        </button>
    </div>
    @endforelse
</div>

{{-- Modal --}}
<div id="addSeanceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
            <h3 class="font-bold text-gray-900">
                <i class="fa-solid fa-pen-to-square mr-2 text-blue-500"></i>
                Enregistrer une séance
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.professeur.journal.store') }}" class="p-5">
            @csrf
            <input type="hidden" name="module_id" value="{{ $selectedModule->id ?? ($modules->first()->id ?? '') }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Horaire</label>
                <div class="flex gap-2">
                    <input type="time" name="heure_debut" required class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="py-2 text-gray-400">—</span>
                    <input type="time" name="heure_fin" required class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de séance</label>
                <select name="type_seance" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="CM">📚 Cours Magistral (CM)</option>
                    <option value="TD">📝 Travaux Dirigés (TD)</option>
                    <option value="TP">💻 Travaux Pratiques (TP)</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Objectif pédagogique</label>
                <input type="text" name="titre" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Introduction aux graphes">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Salle</label>
                <input type="text" name="salle" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Amphi A1">
            </div>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observations</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Déroulement, difficultés rencontrées..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition">Annuler</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('addSeanceModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('addSeanceModal').classList.add('hidden');
}
function editSeance(id) {
    window.location.href = "{{ route('admin.professeur.journal.index') }}/" + id + "/edit";
}
</script>
@endsection