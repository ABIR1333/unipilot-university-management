@extends('layouts.app')
@section('title', $etudiant->nom)
@section('page-title', $etudiant->nom)

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
    {{-- Profile --}}
    <div class="space-y-5">
        <div class="card p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">
                {{ $etudiant->initials }}
            </div>
            <h2 class="text-lg font-bold text-gray-900">{{ $etudiant->nom }}</h2>
            <p class="text-sm text-gray-500">{{ $etudiant->email }}</p>
            <p class="text-xs font-mono text-indigo-600 mt-1">{{ $etudiant->numero_carte }}</p>
            <span class="badge badge-{{ $etudiant->statut_color }} mt-2 inline-flex">{{ $etudiant->statut }}</span>
            <div class="grid grid-cols-3 gap-3 mt-5 pt-4 border-t border-gray-100 text-center">
                <div>
                    <p class="text-xl font-bold {{ $etudiant->moyenne_color }}">{{ number_format($etudiant->moyenne_generale,1) }}</p>
                    <p class="text-xs text-gray-400">Moyenne</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">S{{ $etudiant->semestre_actuel }}</p>
                    <p class="text-xs text-gray-400">Semestre</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ $statsPresence['taux'] }}%</p>
                    <p class="text-xs text-gray-400">Présence</p>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <a href="{{ route('admin.etudiants.edit',$etudiant) }}" class="btn-secondary flex-1 justify-center text-xs py-1.5"><i class="fa-solid fa-pen"></i> Modifier</a>
                <a href="{{ route('admin.etudiants.releve',$etudiant) }}" class="btn-primary flex-1 justify-center text-xs py-1.5"><i class="fa-solid fa-file-pdf"></i> Relevé</a>
            </div>
            <div class="mt-2">
                <a href="{{ route('admin.etudiants.attestation',$etudiant) }}" class="btn-secondary w-full justify-center text-xs py-1.5">
                    <i class="fa-solid fa-certificate"></i> Attestation de scolarité
                </a>
            </div>
        </div>

        <div class="card p-5">
            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Programme</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-xs text-gray-500">Filière</dt><dd class="font-medium">{{ $etudiant->programme->nom }}</dd></div>
                <div><dt class="text-xs text-gray-500">Type</dt><dd>{{ $etudiant->programme->type }}</dd></div>
                <div><dt class="text-xs text-gray-500">Durée</dt><dd>{{ $etudiant->programme->duree_annees }} ans</dd></div>
                <div><dt class="text-xs text-gray-500">Inscription</dt><dd>{{ $etudiant->date_inscription->format('d/m/Y') }}</dd></div>
            </dl>
        </div>

        <div class="card p-5">
            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Présences</h3>
            <div class="grid grid-cols-2 gap-3 text-center text-sm">
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-green-600">{{ $statsPresence['presents'] }}</p>
                    <p class="text-xs text-gray-500">Présences</p>
                </div>
                <div class="bg-red-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-red-600">{{ $statsPresence['absents'] }}</p>
                    <p class="text-xs text-gray-500">Absences</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-yellow-600">{{ $statsPresence['justifies'] }}</p>
                    <p class="text-xs text-gray-500">Justifiées</p>
                </div>
                <div class="bg-indigo-50 rounded-lg p-3">
                    <p class="text-xl font-bold text-indigo-600">{{ $statsPresence['taux'] }}%</p>
                    <p class="text-xs text-gray-500">Taux</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Résultats académiques</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th>MODULE</th>
                        <th class="text-center">CC1 /20</th>
                        <th class="text-center">CC2 /20</th>
                        <th class="text-center">EXAMEN /20</th>
                        <th class="text-center">NOTE FINALE</th>
                        <th class="text-center">MENTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notesByModule as $moduleId => $notes)
                    @php $note = $notes->first(); @endphp
                    <tr>
                        <td class="font-medium text-gray-900">{{ $note->module->nom }}</td>
                        <td class="text-center">{{ $note->note_cc1 !== null ? number_format($note->note_cc1,1) : '—' }}</td>
                        <td class="text-center">{{ $note->note_cc2 !== null ? number_format($note->note_cc2,1) : '—' }}</td>
                        <td class="text-center">{{ $note->note_examen !== null ? number_format($note->note_examen,1) : '—' }}</td>
                        <td class="text-center {{ $note->note_finale_color }}">{{ $note->note_finale !== null ? number_format($note->note_finale,1) : '—' }}</td>
                        <td class="text-center">
                            @if($note->mention)
                            <span class="badge {{ $note->mention_color }}">{{ $note->mention }}</span>
                            @else —
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-gray-400">Aucune note disponible</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Demandes --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Demandes administratives</h3>
            </div>
            @forelse($etudiant->demandes as $demande)
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-900">{{ $demande->type }}</p>
                    <p class="text-xs text-gray-400">{{ $demande->created_at->format('d/m/Y') }} — {{ $demande->motif }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-{{ $demande->statut_color }}">{{ $demande->statut }}</span>
                    @if($demande->statut === 'Approuvée')
                    <a href="{{ route('admin.demandes.telecharger',$demande) }}" class="btn-secondary btn-sm">
                        <i class="fa-solid fa-download"></i>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-sm text-gray-400">Aucune demande</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
