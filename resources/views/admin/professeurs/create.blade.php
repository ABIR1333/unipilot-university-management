@extends('layouts.app')
@section('title','Ajouter un professeur')
@section('page-title','Ajouter un professeur')
@section('content')
<div class="max-w-3xl mx-auto"><div class="card p-6">
<form method="POST" action="{{ route('admin.professeurs.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sm:col-span-2 font-semibold text-gray-900 border-b pb-2">Compte</div>
        <div><label class="form-label">Nom complet *</label><input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Dr. Prénom NOM"></div>
        <div><label class="form-label">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="prenom.nom@upp.fr"></div>
        <div><label class="form-label">Mot de passe *</label><input type="password" name="password" required class="form-input"></div>
        <div><label class="form-label">Téléphone</label><input type="text" name="telephone" value="{{ old('telephone') }}" class="form-input" placeholder="+33 1 42 76 31 00"></div>
        <div class="sm:col-span-2 font-semibold text-gray-900 border-b pb-2 mt-2">Informations académiques</div>
        <div><label class="form-label">Spécialité</label><input type="text" name="specialite" value="{{ old('specialite') }}" class="form-input"></div>
        <div><label class="form-label">Bureau</label><input type="text" name="bureau" value="{{ old('bureau') }}" class="form-input" placeholder="Bureau 101"></div>
        <div><label class="form-label">Date d'embauche *</label><input type="date" name="date_embauche" value="{{ old('date_embauche') }}" required class="form-input"></div>
        <div class="sm:col-span-2">
            <label class="form-label">Modules assignés</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                @foreach($modules as $module)
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="modules[]" value="{{ $module->id }}" class="rounded border-gray-300 text-indigo-600">
                    <span>{{ $module->nom }}</span>
                    <span class="text-xs text-gray-400">({{ $module->programme->code }})</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
        <a href="{{ route('admin.professeurs.index') }}" class="btn-secondary">Annuler</a>
        <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Créer le professeur</button>
    </div>
</form>
</div></div>
@endsection
