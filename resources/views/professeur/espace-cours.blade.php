@extends('layouts.professeur')
@section('title','Espace cours')
@section('page-title','Espace cours')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="relative inline-block">
        <select name="module_id" onchange="this.form.submit()" 
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($modules ?? [] as $m)
            <option value="{{ $m->id }}" {{ ($selectedModule?->id ?? ($modules->first()->id ?? null)) == $m->id ? 'selected' : '' }}>
                {{ $m->nom }} — {{ $m->programme->code ?? 'L3' }} S4
            </option>
            @endforeach
        </select>
        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
    </div>
    
    <button onclick="openAnnonceModal()" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        <i class="fa-solid fa-plus mr-1"></i> Annonce
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Annonces avec style alerte --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-900">
                    <i class="fa-solid fa-bullhorn mr-2 text-blue-500"></i>
                    Annonces
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($annonces ?? [] as $annonce)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex-1">
                            {{-- Alerte style annonce --}}
                            <div class="flex items-start gap-3 p-3 rounded-lg {{ $loop->first ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-white' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full {{ $loop->first ? 'bg-blue-200' : 'bg-gray-100' }} flex items-center justify-center">
                                        <i class="fa-solid {{ $loop->first ? 'fa-bell text-blue-600' : 'fa-file-lines text-gray-500' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-semibold text-gray-900">{{ $annonce->titre }}</h4>
                                        @if($loop->first)
                                        <span class="px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">Nouvelle</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($annonce->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-600 text-sm mt-1">{{ $annonce->contenu }}</p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($annonce->created_at)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('admin.professeur.espace-cours.annonce.destroy', $annonce) }}" class="flex-shrink-0">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-300 hover:text-red-500 transition-colors">
                                        <i class="fa-regular fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-regular fa-newspaper text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500">Aucune annonce pour ce module</p>
                    <p class="text-sm text-gray-400 mt-1">Cliquez sur "Annonce" pour en créer une</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-900">
                    <i class="fa-solid fa-folder-open mr-2 text-blue-500"></i>
                    Documents
                </h3>
                <button onclick="openDocModal()" class="text-blue-600 text-sm hover:text-blue-700 font-medium">+ Déposer</button>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($documents ?? [] as $doc)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-file-pdf text-red-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $doc->titre }}</p>
                                <p class="text-xs text-gray-400">
                                    @php
                                        $size = $doc->taille;
                                        if ($size >= 1048576) {
                                            echo number_format($size / 1048576, 1) . ' MB';
                                        } elseif ($size >= 1024) {
                                            echo number_format($size / 1024, 0) . ' KB';
                                        } else {
                                            echo $size . ' B';
                                        }
                                    @endphp
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-3">

                            <form method="POST" action="{{ route('admin.professeur.espace-cours.document.destroy', $doc) }}" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-xs text-gray-500 hover:text-red-600">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-regular fa-folder-open text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500">Aucun document déposé</p>
                    <p class="text-sm text-gray-400 mt-1">Cliquez sur "Déposer" pour ajouter un document</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Commentaires --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="font-semibold text-gray-900">
                <i class="fa-solid fa-comments mr-2 text-blue-500"></i>
                Commentaires étudiants
            </h3>
        </div>
        <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
            @forelse($commentaires ?? [] as $comment)
            <div class="flex gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-7 h-7 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold uppercase flex-shrink-0">
                    {{ substr($comment->user->name ?? 'E', 0, 1) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-sm text-gray-800">{{ $comment->user->name ?? 'Étudiant' }}</p>
                    <p class="text-gray-600 text-sm mt-0.5">{{ $comment->contenu }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-regular fa-comments text-xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm">Aucun commentaire</p>
            </div>
            @endforelse
        </div>
        
        <div class="p-3 border-t border-gray-100 bg-gray-50">
            <form method="POST" action="{{ route('admin.professeur.espace-cours.commentaire.store') }}" class="flex gap-2">
                @csrf
                <input type="hidden" name="annonce_id" value="{{ $annonces->first()->id ?? '' }}">
                <input type="text" name="contenu" placeholder="Écrire un commentaire..." required 
                       class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Annonce --}}
<div id="addAnnonceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-all">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">
                <i class="fa-solid fa-bullhorn mr-2 text-blue-500"></i>
                Nouvelle annonce
            </h3>
            <button onclick="closeAnnonceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.professeur.espace-cours.annonce.store') }}" class="p-5">
            @csrf
            <input type="hidden" name="module_id" value="{{ $selectedModule->id ?? ($modules->first()->id ?? '') }}">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Titre</label>
                <input type="text" name="titre" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Contenu</label>
                <textarea name="contenu" rows="4" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeAnnonceModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Publier
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Document --}}
<div id="addDocModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-all">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">
                <i class="fa-solid fa-upload mr-2 text-blue-500"></i>
                Déposer un document
            </h3>
            <button onclick="closeDocModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.professeur.espace-cours.document.store') }}" enctype="multipart/form-data" class="p-5">
            @csrf
            <input type="hidden" name="module_id" value="{{ $selectedModule->id ?? ($modules->first()->id ?? '') }}">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Titre</label>
                <input type="text" name="titre" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Fichier (PDF max 20MB)</label>
                <input type="file" name="fichier" accept=".pdf" required class="w-full text-sm">
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeDocModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                    <i class="fa-solid fa-cloud-upload-alt mr-1"></i> Déposer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAnnonceModal() { document.getElementById('addAnnonceModal').classList.remove('hidden'); }
function closeAnnonceModal() { document.getElementById('addAnnonceModal').classList.add('hidden'); }
function openDocModal() { document.getElementById('addDocModal').classList.remove('hidden'); }
function closeDocModal() { document.getElementById('addDocModal').classList.add('hidden'); }
</script>
@endsection