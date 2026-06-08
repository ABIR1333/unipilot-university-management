@extends('layouts.app')
@section('title','Espace cours')
@section('page-title','Espace cours')

@section('content')
<div class="flex items-center justify-between mb-5">
    <h2 class="text-xl font-bold text-gray-900">Espace cours</h2>
    <p class="text-xs text-gray-400 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
</div>

{{-- Module selector + New annonce button --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <form method="GET" class="flex items-center gap-3">
        <div class="relative">
            <select name="module_id" onchange="this.form.submit()" class="form-input pr-8 w-64 appearance-none">
                @foreach($modules as $m)
                <option value="{{ $m->id }}" {{ $selectedModule?->id==$m->id?'selected':'' }}>
                    {{ $m->nom }} — {{ $m->programme->code }}
                </option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="flex gap-2">
        <button onclick="document.getElementById('addDocModal').classList.remove('hidden')" class="btn-secondary">
            <i class="fa-solid fa-upload"></i> Document
        </button>
        <button onclick="document.getElementById('addAnnonceModal').classList.remove('hidden')" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Nouvelle annonce
        </button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
    {{-- Annonces + Documents --}}
    <div class="xl:col-span-2 space-y-5">
        {{-- Annonces --}}
        <div>
            <p class="font-semibold text-gray-900 mb-3">Annonces</p>
            @forelse($annonces as $annonce)
            <div class="card p-5 mb-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $annonce->titre }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $annonce->contenu }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $annonce->creator->name }} · {{ $annonce->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div x-data="{open:false}" class="relative">
                        <button @click="open=!open" class="p-1.5 text-gray-400 hover:text-gray-600 rounded">
                            <i class="fa-solid fa-ellipsis text-sm"></i>
                        </button>
                        <div x-show="open" x-cloak @click.away="open=false"
                             class="absolute right-0 mt-1 w-36 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-10">
                            <form method="POST" action="{{ route('admin.espace-cours.annonces.destroy',$annonce) }}">
                                @csrf @method('DELETE')
                                <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fa-solid fa-trash mr-2"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card p-8 text-center text-gray-400">
                <i class="fa-solid fa-bullhorn text-4xl block mb-2 opacity-20"></i>
                Aucune annonce pour ce module
            </div>
            @endforelse
        </div>

        {{-- Documents --}}
        <div>
            <p class="font-semibold text-gray-900 mb-3">Documents</p>
            @forelse($documents as $doc)
            <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
                <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-file-pdf text-red-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 text-sm truncate">{{ $doc->titre }}</p>
                    <p class="text-xs text-gray-400">{{ $doc->taille_humain }} · {{ $doc->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ $doc->url }}" target="_blank" class="text-indigo-600 hover:underline text-sm font-medium">
                        Télécharger
                    </a>
                    <form method="POST" action="{{ route('admin.espace-cours.documents.destroy',$doc) }}">
                        @csrf @method('DELETE')
                        <button class="p-1 text-gray-400 hover:text-red-600 transition-colors">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-400 py-6">Aucun document</div>
            @endforelse
        </div>
    </div>

    {{-- Commentaires récents --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-900 mb-1">Commentaires récents</h3>
        <p class="text-xs text-gray-400 mb-4">Dernières interactions</p>
        <div class="space-y-4 mb-4 max-h-80 overflow-y-auto">
            @forelse($commentaires as $comment)
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold flex-shrink-0">
                    {{ $comment->user->initials }}
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800">{{ $comment->contenu }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $comment->user->name }} · {{ $comment->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Aucun commentaire</p>
            @endforelse
        </div>
        {{-- Reply box --}}
        @if($annonces->first())
        <form method="POST" action="{{ route('admin.espace-cours.commentaires.store') }}" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="annonce_id" value="{{ $annonces->first()->id }}">
            <input type="text" name="contenu" placeholder="Répondre..." required
                   class="form-input flex-1 text-sm">
            <button type="submit"
                    class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white hover:bg-indigo-700 flex-shrink-0">
                <i class="fa-solid fa-paper-plane text-sm"></i>
            </button>
        </form>
        @endif
    </div>
</div>

{{-- Add Annonce Modal --}}
<div id="addAnnonceModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Nouvelle annonce</h3>
            <button onclick="document.getElementById('addAnnonceModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.espace-cours.annonces.store') }}">
            @csrf
            <input type="hidden" name="module_id" value="{{ $selectedModule?->id }}">
            <div class="space-y-4">
                <div><label class="form-label">Titre *</label><input type="text" name="titre" required class="form-input"></div>
                <div><label class="form-label">Contenu *</label><textarea name="contenu" rows="3" required class="form-input"></textarea></div>
                <div><label class="form-label">Audience</label>
                    <select name="audience" class="form-input">
                        <option value="tous">Tous</option>
                        <option value="etudiants">Étudiants seulement</option>
                        <option value="professeurs">Professeurs seulement</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addAnnonceModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Publier</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add Document Modal --}}
<div id="addDocModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Uploader un document</h3>
            <button onclick="document.getElementById('addDocModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.espace-cours.documents.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="module_id" value="{{ $selectedModule?->id }}">
            <div class="space-y-4">
                <div><label class="form-label">Titre *</label><input type="text" name="titre" required class="form-input" placeholder="Cours Chap.7 — Graphes"></div>
                <div><label class="form-label">Fichier *</label><input type="file" name="fichier" required class="form-input"></div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addDocModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-upload"></i> Uploader</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
