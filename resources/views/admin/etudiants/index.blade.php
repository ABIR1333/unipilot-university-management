@extends('layouts.app')
@section('title','Étudiants')
@section('page-title','Gestion des étudiants')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Gestion des étudiants</h2>
        <p class="text-sm text-gray-400 mt-0.5 flex items-center gap-1">
            <i class="fa-solid fa-rotate text-gray-400 text-xs"></i> Dernière mise à jour : il y a 2 min
        </p>
    </div>
    <a href="{{ route('admin.etudiants.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter un étudiant
    </a>
</div>

{{-- Filters --}}
<div class="card p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="relative flex-1 min-w-48">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher un étudiant..."
                   class="form-input pl-9">
        </div>
        <select name="programme_id" class="form-input w-auto">
            <option value="">Tous les programmes</option>
            @foreach($programmes as $p)
            <option value="{{ $p->id }}" {{ request('programme_id')==$p->id?'selected':'' }}>{{ $p->nom }}</option>
            @endforeach
        </select>
        <select name="statut" class="form-input w-auto">
            <option value="">Tous les statuts</option>
            @foreach(['Actif','Suspendu','Diplômé','Retiré'] as $s)
            <option value="{{ $s }}" {{ request('statut')===$s?'selected':'' }}>{{ $s }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary"><i class="fa-solid fa-filter"></i> Filtrer</button>
        @if(request()->hasAny(['search','programme_id','statut']))
        <a href="{{ route('admin.etudiants.index') }}" class="btn-secondary"><i class="fa-solid fa-xmark"></i> Réinitialiser</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr>
                <th>ÉTUDIANT</th>
                <th>N° CARTE</th>
                <th>PROGRAMME</th>
                <th>SEMESTRE</th>
                <th>MOYENNE</th>
                <th>STATUT</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($etudiants as $etudiant)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ $etudiant->initials }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $etudiant->nom }}</p>
                            <p class="text-xs text-gray-500">{{ $etudiant->user->email ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="font-mono text-sm text-gray-600">{{ $etudiant->numero_carte }}</td>
                <td class="text-sm text-gray-700">{{ $etudiant->programme->nom }}</td>
                <td>
                    <span class="badge badge-blue">S{{ $etudiant->semestre_actuel }}</span>
                </td>
                <td class="{{ $etudiant->moyenne_color }} font-bold">
                    {{ number_format($etudiant->moyenne_generale,1) }}/20
                </td>
                <td>
                    <span class="badge badge-{{ $etudiant->statut_color }}">{{ $etudiant->statut }}</span>
                </td>
                <td>
                    <div class="flex items-center gap-1 justify-end">
                        <a href="{{ route('admin.etudiants.show',$etudiant) }}"
                           class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-colors" title="Voir">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </a>
                        <a href="{{ route('admin.etudiants.edit',$etudiant) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                            <i class="fa-solid fa-pen text-sm"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.etudiants.destroy',$etudiant) }}"
                              onsubmit="return confirm('Supprimer cet étudiant ?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Supprimer">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="py-12 text-center text-gray-400">
                <i class="fa-solid fa-user-graduate text-4xl mb-3 block opacity-20"></i>
                Aucun étudiant trouvé
            </td></tr>
            @endforelse
        </tbody>
    </table>
    @if($etudiants->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
        <span class="text-sm text-gray-500">{{ $etudiants->total() }} étudiant(s) affiché(s)</span>
        <div class="flex items-center gap-1">
            @if($etudiants->onFirstPage())
            <span class="px-2 py-1 text-gray-300 text-sm"><i class="fa-solid fa-chevron-left"></i></span>
            @else
            <a href="{{ $etudiants->previousPageUrl() }}" class="px-2 py-1 text-gray-500 hover:text-indigo-600 text-sm"><i class="fa-solid fa-chevron-left"></i></a>
            @endif
            <span class="text-sm text-gray-600">Page {{ $etudiants->currentPage() }} / {{ $etudiants->lastPage() }}</span>
            @if($etudiants->hasMorePages())
            <a href="{{ $etudiants->nextPageUrl() }}" class="px-2 py-1 text-gray-500 hover:text-indigo-600 text-sm"><i class="fa-solid fa-chevron-right"></i></a>
            @else
            <span class="px-2 py-1 text-gray-300 text-sm"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @else
    <div class="px-5 py-3 border-t border-gray-100">
        <span class="text-sm text-gray-500">{{ $etudiants->total() }} étudiant(s) affiché(s) sur {{ $total }}</span>
    </div>
    @endif
</div>
@endsection