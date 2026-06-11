@extends('layouts.professeur')
@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>
    <p class="text-sm text-gray-500 mt-1">Gérez vos informations personnelles et professionnelles</p>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    <i class="fa-solid fa-exclamation-circle mr-1"></i>
    @foreach($errors->all() as $error)
        <p class="text-xs">{{ $error }}</p>
    @endforeach
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Profile card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-5 text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white text-xl font-bold mx-auto mb-2">
                {{ auth()->user()->initials ?? substr(auth()->user()->name, 0, 2) }}
            </div>
            <h2 class="text-md font-bold text-white">{{ $professeur->user->name ?? $professeur->nom }}</h2>
            <p class="text-blue-100 text-xs mt-0.5">{{ $professeur->user->email ?? $professeur->email }}</p>
            <span class="inline-block mt-2 px-2 py-0.5 bg-green-500/20 text-green-100 text-xs rounded-full">Enseignant actif</span>
        </div>
        
        <div class="p-4 space-y-3">
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-graduation-cap text-gray-400 text-xs mt-0.5"></i>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Spécialité</p>
                    <p class="text-sm font-medium text-gray-900">{{ $professeur->specialite ?? 'Non renseignée' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-building text-gray-400 text-xs mt-0.5"></i>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Bureau</p>
                    <p class="text-sm font-medium text-gray-900">{{ $professeur->bureau ?? 'Non renseigné' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-phone text-gray-400 text-xs mt-0.5"></i>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Téléphone</p>
                    <p class="text-sm font-medium text-gray-900">{{ $professeur->telephone ?? 'Non renseigné' }}</p>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-3">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1.5">Modules ({{ $professeur->modules->count() }})</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($professeur->modules as $m)
                    <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-md">{{ $m->nom }}</span>
                    @endforeach
                    @if($professeur->modules->isEmpty())
                    <p class="text-xs text-gray-400">Aucun module assigné</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Edit forms --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Informations professionnelles --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-900 text-sm">
                    <i class="fa-solid fa-user-pen mr-1.5 text-blue-500 text-xs"></i>
                    Informations professionnelles
                </h3>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('admin.professeur.profil.update') }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nom complet *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                   required class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                   required class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $professeur->telephone) }}"
                                   class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="+33 1 42 76 31 10">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Bureau</label>
                            <input type="text" name="bureau" value="{{ old('bureau', $professeur->bureau) }}"
                                   class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Bâtiment A - Bureau 214">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Spécialité</label>
                            <input type="text" name="specialite" value="{{ old('specialite', $professeur->specialite) }}"
                                   class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Algorithmique & Structures de données">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition">
                            <i class="fa-solid fa-save mr-1 text-xs"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sécurité --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-900 text-sm">
                    <i class="fa-solid fa-lock mr-1.5 text-blue-500 text-xs"></i>
                    Sécurité du compte
                </h3>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('admin.professeur.profil.update') }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nouveau mot de passe</label>
                            <input type="password" name="password" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Min. 8 caractères">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Confirmation</label>
                            <input type="password" name="password_confirmation" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="••••••••">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-4 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-medium transition">
                            <i class="fa-solid fa-key mr-1 text-xs"></i> Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection