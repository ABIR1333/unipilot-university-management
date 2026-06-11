@extends('layouts.etudiant')

@section('title', 'Espace cours')
@section('page-title', 'Espace cours')

@section('content')
<style>
    .annonce-card { transition: all 0.2s; }
    .annonce-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .doc-card { transition: all 0.2s; }
    .doc-card:hover { background-color: #f8fafc; }
    .badge-urgent { background: #fee2e2; color: #dc2626; animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
    .discussion-item { transition: all 0.15s; }
    .discussion-item:hover { background-color: #f8fafc; }
</style>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Espace cours</h1>
            <p class="text-xs text-gray-500 mt-0.5">Annonces, documents et discussions</p>
        </div>
        <div class="bg-indigo-100 rounded-lg px-3 py-1.5">
            <span class="text-indigo-700 text-xs font-semibold">Semestre 4</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        {{-- Annonces --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 text-sm">
                    <i class="fa-solid fa-bullhorn mr-1.5 text-indigo-500 text-xs"></i>
                    Annonces
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="annonce-card p-4 hover:bg-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-red-500 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-semibold text-gray-800 text-sm">Examen final — Algorithmique</h4>
                                <span class="badge-urgent px-1.5 py-0.5 rounded-full text-[9px] font-semibold">Urgent</span>
                            </div>
                            <p class="text-gray-600 text-xs mt-1">L'examen aura lieu le vendredi 13 juin 2025 à 09h00 en Amphi A1. Programme complet disponible sur la plateforme.</p>
                            <div class="flex items-center gap-3 mt-2">
                                <p class="text-[10px] text-gray-400"><i class="fa-regular fa-user mr-1"></i>Dr. Dubois</p>
                                <p class="text-[10px] text-gray-400"><i class="fa-regular fa-calendar mr-1"></i>01/06/2025</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="annonce-card p-4 hover:bg-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-flask text-yellow-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 text-sm">TP noté — Base de données</h4>
                            <p class="text-gray-600 text-xs mt-1">Un TP noté est prévu le mercredi 4 juin. Préparez le TP 6 sur les jointures SQL.</p>
                            <div class="flex items-center gap-3 mt-2">
                                <p class="text-[10px] text-gray-400"><i class="fa-regular fa-user mr-1"></i>Dr. Leclerc</p>
                                <p class="text-[10px] text-gray-400"><i class="fa-regular fa-calendar mr-1"></i>29/05/2025</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="annonce-card p-4 hover:bg-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-pdf text-blue-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 text-sm">Documents du cours disponibles</h4>
                            <p class="text-gray-600 text-xs mt-1">Les slides du chapitre 7 sur les graphes sont maintenant disponibles dans l'espace cours.</p>
                            <div class="flex items-center gap-3 mt-2">
                                <p class="text-[10px] text-gray-400"><i class="fa-regular fa-user mr-1"></i>Dr. Dubois</p>
                                <p class="text-[10px] text-gray-400"><i class="fa-regular fa-calendar mr-1"></i>27/05/2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 text-sm">
                    <i class="fa-solid fa-folder-open mr-1.5 text-indigo-500 text-xs"></i>
                    Documents du cours
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($documents ?? [] as $doc)
                <div class="doc-card p-3 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class="fa-solid fa-file-pdf text-red-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $doc->titre }}</p>
                            <p class="text-[10px] text-gray-400">{{ number_format($doc->taille / 1024, 0) }} KB • {{ $doc->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('etudiant.espace-cours.download', $doc->id) }}" 
                       class="text-indigo-600 hover:text-indigo-700 text-xs font-medium">
                        <i class="fa-solid fa-download mr-1"></i> Télécharger
                    </a>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 text-sm">Aucun document disponible</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Discussions --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="font-semibold text-gray-800 text-sm">
                <i class="fa-solid fa-comments mr-1.5 text-indigo-500 text-xs"></i>
                Discussions
            </h3>
        </div>
        <div class="p-3 space-y-3 max-h-[500px] overflow-y-auto">
            @forelse($commentaires ?? [] as $comment)
            <div class="discussion-item p-2 rounded-lg {{ $loop->first ? 'bg-gray-50' : '' }}">
                <div class="flex gap-2">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-[10px] font-bold uppercase">
                        {{ substr($comment->user->name ?? 'E', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-xs text-gray-800">{{ $comment->user->name ?? 'Étudiant' }}</p>
                        <p class="text-[11px] text-gray-600 mt-0.5">{{ $comment->contenu }}</p>
                        <p class="text-[9px] text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-gray-400 text-sm">Aucun commentaire</div>
            @endforelse
        </div>

        {{-- Répondre --}}
        <div class="p-3 border-t border-gray-100 bg-gray-50">
            <form method="POST" action="{{ route('etudiant.espace-cours.commentaire.store') }}" class="flex gap-2">
                @csrf
                <input type="hidden" name="annonce_id" value="{{ $annonces->first()->id ?? 1 }}">
                <input type="text" name="contenu" placeholder="Votre message..." required 
                       class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-paper-plane text-[10px]"></i>
                </button>
            </form>
            @if(session('success'))
            <div class="mt-2 p-2 bg-green-100 text-green-700 text-xs rounded">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-5 p-3 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
    <div class="flex items-center justify-between text-[10px]">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span><span class="text-gray-600">Nouveau</span></div>
            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span><span class="text-gray-600">Réponse professeur</span></div>
            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-300"></span><span class="text-gray-600">Lu</span></div>
        </div>
        <div class="text-gray-400">
            <i class="fa-regular fa-eye mr-1"></i> {{ $commentaires->count() ?? 0 }} messages
        </div>
    </div>
</div>
@endsection