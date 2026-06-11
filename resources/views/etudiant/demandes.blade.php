@extends('layouts.etudiant')

@section('title', 'Demandes administratives')
@section('page-title', 'Demandes administratives')

@section('content')
<style>
    .demande-card { transition: all 0.2s; }
    .demande-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .status-badge { padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.65rem; font-weight: 600; }
    .status-en-attente { background: #fef3c7; color: #d97706; }
    .status-approuve { background: #dcfce7; color: #166534; }
    .status-rejete { background: #fee2e2; color: #dc2626; }
    .modal-overlay { transition: all 0.3s ease; }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Demandes administratives</h1>
    <p class="text-xs text-gray-500 mt-0.5">Soumettez et suivez vos demandes administratives</p>
</div>

{{-- Types de demandes --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="demande-card bg-white rounded-xl border border-gray-200 shadow-sm p-4 cursor-pointer" onclick="openDemandeModal('Attestation de scolarité')">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center mb-3">
            <i class="fa-solid fa-certificate text-indigo-600 text-lg"></i>
        </div>
        <h3 class="font-semibold text-gray-900 text-sm">Attestation de scolarité</h3>
        <p class="text-[11px] text-gray-500 mt-1">Justificatif d'inscription en cours</p>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                <i class="fa-regular fa-hourglass-half"></i>
                <span>48h</span>
            </div>
            <span class="text-indigo-600 text-xs font-medium flex items-center gap-0.5">
                Demander <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </span>
        </div>
    </div>

    <div class="demande-card bg-white rounded-xl border border-gray-200 shadow-sm p-4 cursor-pointer" onclick="openDemandeModal('Relevé de notes')">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center mb-3">
            <i class="fa-solid fa-table-list text-green-600 text-lg"></i>
        </div>
        <h3 class="font-semibold text-gray-900 text-sm">Relevé de notes</h3>
        <p class="text-[11px] text-gray-500 mt-1">Résultats officiels avec mentions</p>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                <i class="fa-regular fa-hourglass-half"></i>
                <span>72h</span>
            </div>
            <span class="text-indigo-600 text-xs font-medium flex items-center gap-0.5">
                Demander <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </span>
        </div>
    </div>

    <div class="demande-card bg-white rounded-xl border border-gray-200 shadow-sm p-4 cursor-pointer" onclick="openDemandeModal('Certificat d\'inscription')">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
            <i class="fa-solid fa-file-certificate text-amber-600 text-lg"></i>
        </div>
        <h3 class="font-semibold text-gray-900 text-sm">Certificat d'inscription</h3>
        <p class="text-[11px] text-gray-500 mt-1">Pour démarches administratives</p>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                <i class="fa-regular fa-hourglass-half"></i>
                <span>24h</span>
            </div>
            <span class="text-indigo-600 text-xs font-medium flex items-center gap-0.5">
                Demander <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </span>
        </div>
    </div>
</div>

{{-- Mes demandes --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <h3 class="font-semibold text-gray-800 text-sm">
            <i class="fa-solid fa-list-check mr-1.5 text-indigo-500 text-xs"></i>
            Mes demandes
        </h3>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($mesDemandes ?? [] as $demande)
        <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg 
                    @if($demande->type == 'Attestation de scolarité') bg-indigo-100
                    @elseif($demande->type == 'Relevé de notes') bg-green-100
                    @else bg-amber-100 @endif flex items-center justify-center">
                    <i class="fa-solid 
                        @if($demande->type == 'Attestation de scolarité') fa-certificate text-indigo-600
                        @elseif($demande->type == 'Relevé de notes') fa-table-list text-green-600
                        @else fa-file-certificate text-amber-600 @endif text-sm"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $demande->type }}</p>
                    <p class="text-[10px] text-gray-400">Soumis le {{ $demande->created_at->format('d/m/Y') }} • {{ $demande->motif ?? 'Aucun motif' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="status-badge 
                    @if($demande->statut == 'En attente') status-en-attente
                    @elseif($demande->statut == 'Approuvée') status-approuve
                    @else status-rejete @endif">
                    {{ $demande->statut }}
                </span>
                @if($demande->statut == 'Approuvée')
                <a href="{{ route('etudiant.demandes.telecharger', $demande) }}" 
                   class="text-indigo-600 text-xs hover:text-indigo-700 flex items-center gap-1">
                    <i class="fa-solid fa-download text-[10px]"></i> PDF
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="px-4 py-8 text-center text-gray-400 text-sm">
            Aucune demande soumise
        </div>
        @endforelse
    </div>
</div>

{{-- Modal --}}
<div id="demandeModal" class="modal-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-indigo-500 text-sm"></i>
                <h3 class="font-bold text-gray-900">Nouvelle demande</h3>
            </div>
            <button onclick="closeModal()" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-gray-500 text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('etudiant.demandes.store') }}" class="p-5">
            @csrf
            <div class="mb-3">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Type de demande</label>
                <input type="text" name="type" id="demandeTypeInput" readonly class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700">
                <input type="hidden" name="type" id="demandeTypeHidden">
            </div>
            <div class="mb-4">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Motif / Description</label>
                <textarea name="motif" rows="4" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none" placeholder="Précisez le motif de votre demande..."></textarea>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="closeModal()" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition">Annuler</button>
                <button type="submit" class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                    <i class="fa-solid fa-paper-plane mr-1 text-xs"></i> Soumettre
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDemandeModal(type) {
    document.getElementById('demandeTypeInput').value = type;
    document.getElementById('demandeTypeHidden').value = type;
    document.getElementById('demandeModal').classList.add('flex');
    document.getElementById('demandeModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('demandeModal').classList.remove('flex');
    document.getElementById('demandeModal').classList.add('hidden');
    document.getElementById('demandeTypeInput').value = '';
    document.getElementById('demandeTypeHidden').value = '';
}
</script>
@endsection