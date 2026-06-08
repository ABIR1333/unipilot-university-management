@extends('layouts.app')
@section('title','Demandes administratives')
@section('page-title','Demandes administratives')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Demandes administratives</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
</div>

{{-- Tabs --}}
<div class="flex gap-2 mb-5">
    @foreach(['En attente','Approuvées','Rejetées'] as $tab)
    @php $key = $tab === 'Approuvées' ? 'Approuvée' : ($tab === 'Rejetées' ? 'Rejetée' : 'En attente'); @endphp
    <a href="{{ route('admin.demandes.index',['statut'=>$key]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors
              {{ $statut === $key ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        {{ $tab }} ({{ $counts[$key] ?? 0 }})
    </a>
    @endforeach
</div>

{{-- Demandes list --}}
<div class="space-y-3">
    @forelse($demandes as $demande)
    <div class="card p-5 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-file-lines text-indigo-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-semibold text-gray-900">{{ $demande->type }}</p>
            <p class="text-sm text-gray-500">{{ $demande->etudiant->nom }} · {{ $demande->created_at->format('d/m/Y') }}</p>
            @if($demande->motif)
            <p class="text-xs text-gray-400 mt-0.5">Motif : {{ $demande->motif }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <span class="badge badge-{{ $demande->statut_color }}">{{ $demande->statut }}</span>

            @if($demande->statut === 'En attente')
            {{-- Approve --}}
            <div x-data="{open:false}" class="relative">
                <button @click="open=!open" class="btn-success btn-sm">
                    <i class="fa-solid fa-check"></i> Approuver
                </button>
                <div x-show="open" x-cloak @click.away="open=false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 p-4 z-10">
                    <form method="POST" action="{{ route('admin.demandes.approuver',$demande) }}">
                        @csrf
                        <p class="font-semibold text-sm text-gray-900 mb-2">Approuver la demande</p>
                        <textarea name="commentaire" rows="2" placeholder="Commentaire optionnel"
                                  class="form-input text-xs mb-3"></textarea>
                        <div class="flex gap-2">
                            <button type="button" @click="open=false" class="btn-secondary flex-1 text-xs py-1.5">Annuler</button>
                            <button type="submit" class="btn-success flex-1 text-xs py-1.5">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Reject --}}
            <div x-data="{open:false}" class="relative">
                <button @click="open=!open" class="btn-danger btn-sm">
                    <i class="fa-solid fa-xmark"></i> Rejeter
                </button>
                <div x-show="open" x-cloak @click.away="open=false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 p-4 z-10">
                    <form method="POST" action="{{ route('admin.demandes.rejeter',$demande) }}">
                        @csrf
                        <p class="font-semibold text-sm text-gray-900 mb-2">Rejeter la demande</p>
                        <textarea name="commentaire" rows="2" placeholder="Motif du rejet" required
                                  class="form-input text-xs mb-3"></textarea>
                        <div class="flex gap-2">
                            <button type="button" @click="open=false" class="btn-secondary flex-1 text-xs py-1.5">Annuler</button>
                            <button type="submit" class="btn-danger flex-1 text-xs py-1.5">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>

            @elseif($demande->statut === 'Approuvée')
            <a href="{{ route('admin.demandes.telecharger',$demande) }}" class="btn-primary btn-sm">
                <i class="fa-solid fa-download"></i> Télécharger
            </a>
            @endif
        </div>
    </div>
    @empty
    <div class="card p-12 text-center text-gray-400">
        <i class="fa-solid fa-file-circle-check text-5xl block mb-3 opacity-20"></i>
        Aucune demande {{ strtolower($statut) }}
    </div>
    @endforelse
</div>
@endsection
