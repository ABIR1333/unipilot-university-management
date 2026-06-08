@extends('layouts.app')
@section('title','Modifier le professeur')
@section('page-title','Modifier le professeur')
@section('content')
<div class="max-w-3xl mx-auto"><div class="card p-6">
<form method="POST" action="{{ route('admin.professeurs.update',$professeur) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div><label class="form-label">Nom complet *</label><input type="text" name="name" value="{{ old('name',$professeur->nom) }}" required class="form-input"></div>
        <div><label class="form-label">Email *</label><input type="email" name="email" value="{{ old('email',$professeur->email) }}" required class="form-input"></div>
        <div><label class="form-label">Spécialité</label><input type="text" name="specialite" value="{{ old('specialite',$professeur->specialite) }}" class="form-input"></div>
        <div><label class="form-label">Bureau</label><input type="text" name="bureau" value="{{ old('bureau',$professeur->bureau) }}" class="form-input"></div>
        <div><label class="form-label">Téléphone</label><input type="text" name="telephone" value="{{ old('telephone',$professeur->telephone) }}" class="form-input"></div>
        <div><label class="form-label">Statut *</label>
            <select name="statut" required class="form-input">
                @foreach(['Actif','Congé','Inactif'] as $s)
                <option value="{{ $s }}" {{ old('statut',$professeur->statut)===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2">
            <label class="form-label">Modules assignés</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                @foreach($modules as $module)
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                           {{ $professeur->modules->contains($module->id)?'checked':'' }}
                           class="rounded border-gray-300 text-indigo-600">
                    <span>{{ $module->nom }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
        <a href="{{ route('admin.professeurs.index') }}" class="btn-secondary">Annuler</a>
        <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> Enregistrer</button>
    </div>
</form>
</div></div>
@endsection
