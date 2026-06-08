@extends('layouts.app')
@section('title','Ajouter un étudiant')
@section('page-title','Ajouter un étudiant')

@section('content')
<div class="max-w-3xl mx-auto">
<div class="card p-6">
    <form method="POST" action="{{ route('admin.etudiants.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2 text-base font-semibold text-gray-900 border-b pb-2">Informations personnelles</div>
            <div>
                <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Prénom NOM">
            </div>
            <div>
                <label class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="etudiant@etu.upp.fr">
            </div>
            <div>
                <label class="form-label">Mot de passe <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="form-input" placeholder="Min. 8 caractères">
            </div>
            <div>
                <label class="form-label">Photo de profil</label>
                <input type="file" name="avatar" accept="image/*" class="form-input">
            </div>

            <div class="sm:col-span-2 text-base font-semibold text-gray-900 border-b pb-2 mt-2">Informations académiques</div>
            <div>
                <label class="form-label">Programme <span class="text-red-500">*</span></label>
                <select name="programme_id" required class="form-input">
                    <option value="">Sélectionner un programme</option>
                    @foreach($programmes as $p)
                    <option value="{{ $p->id }}" {{ old('programme_id')==$p->id?'selected':'' }}>{{ $p->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Semestre actuel <span class="text-red-500">*</span></label>
                <select name="semestre_actuel" required class="form-input">
                    @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}" {{ old('semestre_actuel',1)==$i?'selected':'' }}>Semestre {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label">Date d'inscription <span class="text-red-500">*</span></label>
                <input type="date" name="date_inscription" value="{{ old('date_inscription',today()->toDateString()) }}" required class="form-input">
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
            <a href="{{ route('admin.etudiants.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Créer l'étudiant</button>
        </div>
    </form>
</div>
</div>
@endsection
