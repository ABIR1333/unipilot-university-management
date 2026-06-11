@extends('layouts.professeur')

@section('title', 'Modifier la séance')
@section('page-title', 'Modifier la séance')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Modifier la séance</h1>
    <p class="text-sm text-gray-500 mt-1">Modifiez les informations de votre séance pédagogique</p>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="font-semibold text-gray-900">
                <i class="fa-solid fa-pen-to-square mr-2 text-blue-500"></i>
                Informations de la séance
            </h3>
        </div>
        
        <div class="p-5">
            <form method="POST" action="{{ route('admin.professeur.journal.update', $entree->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Module</label>
                    <div class="relative">
                        <select name="module_id" class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-600 text-sm cursor-not-allowed" disabled>
                            <option value="{{ $selectedModule->id }}">{{ $selectedModule->nom }}</option>
                        </select>
                        <input type="hidden" name="module_id" value="{{ $selectedModule->id }}">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Le module ne peut pas être modifié</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Titre *</label>
                    <input type="text" name="titre" required value="{{ $entree->titre }}" 
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Ex: Introduction aux graphes">
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Type de séance</label>
                    <div class="relative">
                        <select name="type_seance" class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-600 text-sm cursor-not-allowed" disabled>
                            <option value="{{ $entree->type_seance }}">{{ $entree->type_seance == 'CM' ? 'Cours Magistral' : ($entree->type_seance == 'TD' ? 'Travaux Dirigés' : 'Travaux Pratiques') }}</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Date</label>
                        <div class="relative">
                            <input type="date" name="date" value="{{ $entree->date }}" 
                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-600 text-sm cursor-not-allowed" disabled>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Heure</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="time" name="heure_debut" value="{{ $entree->heure_debut }}" 
                                       class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-600 text-sm cursor-not-allowed" disabled>
                            </div>
                            <span class="py-2 text-gray-400 text-sm">-</span>
                            <div class="relative flex-1">
                                <input type="time" name="heure_fin" value="{{ $entree->heure_fin }}" 
                                       class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-600 text-sm cursor-not-allowed" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Salle</label>
                    <div class="relative">
                        <input type="text" name="salle" value="{{ $entree->salle }}" 
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-600 text-sm cursor-not-allowed" disabled
                               placeholder="Non spécifiée">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Observations</label>
                    <textarea name="notes" rows="4" 
                              class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                              placeholder="Déroulement du cours, difficultés rencontrées, points à améliorer...">{{ $entree->notes }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Seul ce champ peut être modifié</p>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.professeur.journal.index') }}" 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium text-center hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Annuler
                    </a>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-save mr-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection