@extends('layouts.app')
@section('title','Professeurs')
@section('page-title','Gestion des professeurs')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Gestion des professeurs</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
    <a href="{{ route('admin.professeurs.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter un professeur
    </a>
</div>

{{-- Search --}}
<div class="card p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="relative flex-1 min-w-48">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un professeur..."
                   class="form-input pl-9">
        </div>
        <select name="statut" class="form-input w-auto">
            <option value="">Tous les statuts</option>
            @foreach(['Actif','Congé','Inactif'] as $s)
            <option value="{{ $s }}" {{ request('statut')===$s?'selected':'' }}>{{ $s }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary"><i class="fa-solid fa-filter"></i> Filtrer</button>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr>
                <th>PROFESSEUR</th>
                <th>SPÉCIALITÉ</th>
                <th>MODULES ASSIGNÉS</th>
                <th>CONTACT</th>
                <th>STATUT</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($professeurs as $prof)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ $prof->initials }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $prof->nom }}</p>
                            <p class="text-xs text-gray-400">{{ $prof->nombre_etudiants }} étudiants</p>
                        </div>
                    </div>
                </td>
                <td class="text-sm text-gray-600 max-w-xs truncate">{{ $prof->specialite ?? '—' }}</td>
                <td>
                    <div class="flex flex-wrap gap-1">
                        @foreach($prof->modules->take(2) as $module)
                        <span class="badge badge-blue text-xs">{{ $module->nom }}</span>
                        @endforeach
                        @if($prof->modules->count() > 2)
                        <span class="badge badge-gray text-xs">+{{ $prof->modules->count()-2 }}</span>
                        @endif
                    </div>
                </td>
                <td class="text-sm">
                    <p class="text-gray-700">{{ $prof->email }}</p>
                    <p class="text-xs text-gray-400">{{ $prof->telephone ?? '' }}</p>
                </td>
                <td>
                    <span class="badge badge-{{ $prof->statut_color }}">{{ $prof->statut }}</span>
                </td>
                <td>
                    <div class="flex items-center gap-1 justify-end">
                        <a href="{{ route('admin.professeurs.edit',$prof) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors">
                            <i class="fa-solid fa-pen text-sm"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.professeurs.destroy',$prof) }}"
                              onsubmit="return confirm('Supprimer ce professeur ?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-12 text-center text-gray-400">Aucun professeur trouvé</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($professeurs->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $professeurs->links() }}</div>
    @endif
</div>
@endsection
