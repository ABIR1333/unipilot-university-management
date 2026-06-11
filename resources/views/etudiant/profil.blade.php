@extends('layouts.etudiant')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<style>
    .profile-card { transition: all 0.2s; }
    .info-row { transition: all 0.15s; }
    .info-row:hover { background-color: #f8fafc; }
    .avatar-initial {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        box-shadow: 0 8px 20px -5px rgba(79,70,229,0.3);
    }
</style>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Carte de profil --}}
    <div class="lg:col-span-1">
        <div class="profile-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-5">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5 text-center">
                <div class="avatar-initial w-20 h-20 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white text-3xl font-bold mx-auto mb-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                </div>
                <h2 class="text-lg font-bold text-white">{{ auth()->user()->name }}</h2>
                <p class="text-indigo-200 text-xs mt-0.5">{{ auth()->user()->email }}</p>
                <span class="inline-block mt-2 px-2.5 py-0.5 bg-green-500/20 text-green-100 text-[10px] rounded-full font-semibold">Étudiant actif</span>
            </div>
            <div class="p-4 space-y-3">
                <div class="info-row flex items-center gap-3 p-2 rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-id-card text-indigo-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide">N° étudiant</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $etudiant->num_etudiant ?? '20241001' }}</p>
                    </div>
                </div>
                <div class="info-row flex items-center gap-3 p-2 rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide">Programme</p>
                        <p class="text-sm font-semibold text-gray-900">Licence Informatique</p>
                    </div>
                </div>
                <div class="info-row flex items-center gap-3 p-2 rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-week text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide">Semestre</p>
                        <p class="text-sm font-semibold text-gray-900">S4</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulaire d'édition --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 text-sm">
                    <i class="fa-solid fa-user-pen mr-1.5 text-indigo-500 text-xs"></i>
                    Informations personnelles
                </h3>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('etudiant.profil.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Prénom</label>
                            <input type="text" name="prenom" value="{{ explode(' ', auth()->user()->name)[1] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ $etudiant->date_naissance ?? '2003-03-14' }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Nationalité</label>
                        <input type="text" name="nationalite" value="{{ $etudiant->nationalite ?? 'Française' }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Adresse</label>
                        <textarea name="adresse" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ $etudiant->adresse ?? '12 rue Pasteur, 75015 Paris' }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Téléphone</label>
                        <input type="tel" name="telephone" value="{{ $etudiant->telephone ?? '+33 6 12 34 56 78' }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                            <i class="fa-solid fa-save mr-1 text-xs"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sécurité --}}
        <div class="mt-5 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 text-sm">
                    <i class="fa-solid fa-lock mr-1.5 text-indigo-500 text-xs"></i>
                    Sécurité du compte
                </h3>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('etudiant.profil.update') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide mb-1">Nouveau mot de passe</label>
                            <input type="password" name="password" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Min. 8 caractères">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition">
                            <i class="fa-solid fa-key mr-1 text-xs"></i> Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection