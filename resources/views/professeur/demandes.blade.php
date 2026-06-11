@extends('layouts.professeur')
@section('title','Demandes administratives')
@section('page-title','Demandes administratives')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Demandes administratives</h1>
    <p class="text-sm text-gray-500 mt-1">Soumettez et suivez vos demandes administratives</p>
</div>

{{-- Types de demandes disponibles --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @foreach($typesDemandes as $td)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-3">
            <i class="fa-solid fa-file-alt text-white text-sm"></i>
        </div>
        <h3 class="font-semibold text-gray-900 text-md">{{ $td['type'] }}</h3>
        <p class="text-xs text-gray-500 mt-1">{{ $td['desc'] }}</p>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-1 text-xs text-gray-400">
                <i class="fa-regular fa-hourglass-half text-xs"></i>
                <span>Délai: {{ $td['delai'] }}</span>
            </div>
            <button onclick="openDemandeModal('{{ $td['type'] }}')"
                    class="inline-flex items-center gap-1 text-blue-600 font-semibold text-xs hover:text-blue-700 transition-colors">
                Demander <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- Mes demandes --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <h3 class="font-semibold text-gray-900 text-sm">
            <i class="fa-solid fa-list-check mr-1.5 text-blue-500 text-xs"></i>
            Mes demandes
        </h3>
    </div>
    
    @forelse($mesDemandes as $demande)
    <div class="px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-file-lines text-blue-600 text-xs"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-900 text-sm">{{ $demande->type }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <p class="text-xs text-gray-400">
                        <i class="fa-regular fa-calendar mr-1 text-xs"></i>
                        {{ $demande->created_at->format('d/m/Y') }}
                    </p>
                    @if($demande->motif)
                    <p class="text-xs text-gray-400">
                        <i class="fa-regular fa-message mr-1 text-xs"></i>
                        {{ Str::limit($demande->motif, 40) }}
                    </p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    @if($demande->statut === 'Approuvée') bg-green-100 text-green-700
                    @elseif($demande->statut === 'Rejetée') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ $demande->statut }}
                </span>
                @if($demande->statut === 'Approuvée')
                <a href="{{ route('admin.professeur.demandes.telecharger', $demande) }}"
                   class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-download text-xs"></i> PDF
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="px-4 py-8 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <i class="fa-solid fa-file-circle-plus text-xl text-gray-300"></i>
        </div>
        <p class="text-sm text-gray-500">Aucune demande soumise</p>
        <p class="text-xs text-gray-400 mt-1">Cliquez sur une carte ci-dessus pour faire une demande</p>
    </div>
    @endforelse
</div>

{{-- Demande Modal --}}
<div id="demandeModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-all">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-md">
                <i class="fa-solid fa-pen-to-square mr-1.5 text-blue-500 text-sm"></i>
                Nouvelle demande
            </h3>
            <button onclick="closeDemandeModal()"
                    class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fa-solid fa-xmark text-md"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.professeur.demandes.store') }}" class="p-5">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                        Type de demande *
                    </label>
                    <select name="type" id="demandeType" required 
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                        @foreach($typesDemandes as $td)
                        <option value="{{ $td['type'] }}">{{ $td['type'] }}</option>
                        @endforeach
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                        Motif / Description
                    </label>
                    <textarea name="motif" rows="3" 
                              class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition resize-none"
                              placeholder="Précisez le motif de votre demande..."></textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeDemandeModal()"
                            class="flex-1 px-3 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium transition-all shadow-sm">
                        <i class="fa-solid fa-paper-plane mr-1 text-xs"></i> Soumettre
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openDemandeModal(type) {
    document.getElementById('demandeType').value = type;
    document.getElementById('demandeModal').classList.remove('hidden');
}

function closeDemandeModal() {
    document.getElementById('demandeModal').classList.add('hidden');
}
</script>
@endpush
@endsection