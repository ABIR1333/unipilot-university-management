@extends('layouts.app')
@section('title','Gestion des salles')
@section('page-title','Gestion des salles')

@section('content')
<div class="flex items-center justify-between mb-5">
    <h2 class="text-xl font-bold text-gray-900">Gestion des salles</h2>
    <p class="text-xs text-gray-400 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
    {{-- Salles list --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900">Salles disponibles</h3>
            <button onclick="document.getElementById('addSalleModal').classList.remove('hidden')" class="btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Ajouter
            </button>
        </div>
        <div class="space-y-3">
            @foreach($salles as $salle)
            <div class="card p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-chalkboard text-indigo-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ $salle->nom }}</p>
                    <p class="text-xs text-gray-500">{{ $salle->type }} · {{ $salle->capacite }} places · {{ $salle->batiment }}</p>
                </div>
                <span class="badge badge-{{ $salle->statut_color }}">{{ $salle->statut }}</span>
                <div class="flex gap-1">
                    <form method="POST" action="{{ route('admin.salles.destroy',$salle) }}"
                          onsubmit="return confirm('Supprimer cette salle ?')">
                        @csrf @method('DELETE')
                        <button class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Réservations --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900">Calendrier de réservations</h3>
            <button onclick="document.getElementById('addReservModal').classList.remove('hidden')" class="btn-secondary btn-sm">
                <i class="fa-solid fa-plus"></i> Réserver
            </button>
        </div>
        <div class="space-y-3">
            @forelse($reservations as $res)
            <div class="card p-4 flex items-center gap-4">
                <span class="badge badge-blue flex-shrink-0">{{ $res->salle->nom }}</span>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 text-sm">{{ $res->titre }}</p>
                    <p class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($res->date)->isoFormat('dddd D MMMM') }} ·
                        {{ \Carbon\Carbon::parse($res->heure_debut)->format('H:i') }}–{{ \Carbon\Carbon::parse($res->heure_fin)->format('H:i') }}
                        @if($res->professeur) · {{ $res->professeur->nom }} @endif
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.salles.reservations.destroy',$res) }}">
                    @csrf @method('DELETE')
                    <button class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
            @empty
            <div class="card p-8 text-center text-gray-400">Aucune réservation à venir</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add Salle Modal --}}
<div id="addSalleModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Ajouter une salle</h3>
            <button onclick="document.getElementById('addSalleModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.salles.store') }}">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Nom *</label><input type="text" name="nom" required class="form-input" placeholder="Salle 201"></div>
                    <div><label class="form-label">Bâtiment</label><input type="text" name="batiment" class="form-input" placeholder="Bât. A"></div>
                    <div><label class="form-label">Capacité *</label><input type="number" name="capacite" required min="1" class="form-input"></div>
                    <div><label class="form-label">Type *</label>
                        <select name="type" required class="form-input">
                            @foreach(['Amphithéâtre','Cours','TD','Laboratoire','Salle informatique'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2"><label class="form-label">Statut *</label>
                        <select name="statut" required class="form-input">
                            @foreach(['Disponible','Occupée','Maintenance'] as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addSalleModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add Réservation Modal --}}
<div id="addReservModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Nouvelle réservation</h3>
            <button onclick="document.getElementById('addReservModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.salles.reservations.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="form-label">Salle *</label>
                    <select name="salle_id" required class="form-input">
                        @foreach($salles as $s)
                        <option value="{{ $s->id }}">{{ $s->nom }} ({{ $s->capacite }} places)</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label">Titre *</label><input type="text" name="titre" required class="form-input" placeholder="Examen Algorithmique"></div>
                <div class="grid grid-cols-3 gap-3">
                    <div><label class="form-label">Date *</label><input type="date" name="date" required class="form-input"></div>
                    <div><label class="form-label">Début *</label><input type="time" name="heure_debut" required class="form-input"></div>
                    <div><label class="form-label">Fin *</label><input type="time" name="heure_fin" required class="form-input"></div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addReservModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Réserver</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
