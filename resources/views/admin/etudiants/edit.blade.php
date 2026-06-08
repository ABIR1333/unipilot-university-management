@extends('layouts.app')
@section('title','Modifier l\'étudiant')
@section('page-title','Modifier l\'étudiant')

@section('content')
<div class="max-w-3xl mx-auto">
<div class="card p-6">
    <form method="POST" action="{{ route('admin.etudiants.update',$etudiant) }}" enctype="multipart/form-data">
        @csrf @method('PATCH')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2 text-base font-semibold text-gray-900 border-b pb-2">Informations personnelles</div>
            <div>
                <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name',$etudiant->nom) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email',$etudiant->email) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Statut <span class="text-red-500">*</span></label>
                <select name="statut" required class="form-input">
                    @foreach(['Actif','Suspendu','Diplômé','Retiré'] as $s)
                    <option value="{{ $s }}" {{ old('statut',$etudiant->statut)===$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div x-data="{preview:'{{ $etudiant->avatar_url }}'}">
                <label class="form-label">Photo de profil</label>
                <div class="flex items-center gap-3">
                    <img :src="preview" class="w-10 h-10 rounded-full object-cover">
                    <input type="file" name="avatar" accept="image/*" class="form-input"
                           @change="preview=URL.createObjectURL($event.target.files[0])">
                </div>
            </div>

            <div class="sm:col-span-2 text-base font-semibold text-gray-900 border-b pb-2 mt-2">Informations académiques</div>
            <div>
                <label class="form-label">Programme <span class="text-red-500">*</span></label>
                <select name="programme_id" required class="form-input">
                    @foreach($programmes as $p)
                    <option value="{{ $p->id }}" {{ old('programme_id',$etudiant->programme_id)==$p->id?'selected':'' }}>{{ $p->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Semestre actuel <span class="text-red-500">*</span></label>
                <select name="semestre_actuel" required class="form-input">
                    @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}" {{ old('semestre_actuel',$etudiant->semestre_actuel)==$i?'selected':'' }}>Semestre {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
            <a href="{{ route('admin.etudiants.show',$etudiant) }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> Enregistrer</button>
        </div>
    </form>
</div>
</div>
@endsection
