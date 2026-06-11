@extends('layouts.professeur')
@section('title', 'Réservation de salle')
@section('page-title', 'Réservation de salle')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        Réservation de salle
    </h1>
    <p class="text-sm text-gray-500 mt-1">
        Réservez une salle pour vos cours, TD ou examens.
    </p>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach
</div>
@endif

<div class="grid lg:grid-cols-2 gap-6">

    {{-- Salles --}}
    <div>
        <h2 class="text-md font-semibold mb-3 text-gray-700">
            Salles disponibles
        </h2>

        <div class="space-y-3">
            @foreach($salles as $salle)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-chalkboard text-blue-600 text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">
                                {{ $salle->nom }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ $salle->type }} · {{ $salle->capacite }} places
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-{{ $salle->statut_color }} text-xs">
                            {{ $salle->statut }}
                        </span>
                        @if($salle->statut !== 'Occupée')
                        <button type="button"
                                onclick="remplirFormulaire({{ $salle->id }}, '{{ addslashes($salle->nom) }}')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-xl text-xs font-medium transition-colors">
                            Réserver
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Réservations --}}
        @if($mesReservations->count())
        <h2 class="text-md font-semibold mt-6 mb-3 text-gray-700">
            Mes réservations
        </h2>
        <div class="space-y-2" id="mesReservations">
            @foreach($mesReservations as $res)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-3" id="reservation-{{ $res->id }}">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">
                            {{ $res->titre }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $res->salle->nom }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($res->date)->format('d/m/Y') }}
                            ·
                            {{ \Carbon\Carbon::parse($res->heure_debut)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($res->heure_fin)->format('H:i') }}
                        </p>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.professeur.reservations.destroy', $res) }}"
                          onsubmit="return confirm('Annuler cette réservation ?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 text-sm">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Formulaire --}}
    <div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-5">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">Nouvelle réservation</h3>
            
            <form method="POST"
                  id="reservationForm"
                  action="{{ route('admin.professeur.reservations.store') }}"
                  class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Salle</label>
                    <select id="salleSelect"
                            name="salle_id"
                            required
                            class="w-full h-9 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choisir une salle</option>
                        @foreach($salles as $salle)
                        <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Module</label>
                    <select name="module_id"
                            class="w-full h-9 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner</option>
                        @foreach($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Titre</label>
                    <input type="text"
                           name="titre"
                           id="titre"
                           required
                           placeholder="Ex: Examen, TD..."
                           class="w-full h-9 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Date</label>
                    <input type="date"
                           name="date"
                           id="date"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full h-9 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Début</label>
                        <input type="time"
                               name="heure_debut"
                               id="heure_debut"
                               required
                               class="w-full h-9 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Fin</label>
                        <input type="time"
                               name="heure_fin"
                               id="heure_fin"
                               required
                               class="w-full h-9 px-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit"
                        class="w-full h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition mt-2">
                    <i class="fa-solid fa-check mr-1"></i> Confirmer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function remplirFormulaire(salleId, salleNom) {
    let select = document.getElementById('salleSelect');
    if(select) {
        select.value = salleId;
        select.scrollIntoView({behavior: 'smooth', block: 'center'});
        
        // Optionnel: pré-remplir le titre avec la salle
        let titre = document.getElementById('titre');
        if(titre && titre.value === '') {
            titre.value = 'Cours en ' + salleNom;
        }
        
        // Définir les heures par défaut
        let now = new Date();
        let currentHour = now.getHours();
        let currentMinute = now.getMinutes();
        
        let startHour = currentHour + 1;
        let endHour = startHour + 2;
        
        let startTime = startHour.toString().padStart(2, '0') + ':' + currentMinute.toString().padStart(2, '0');
        let endTime = endHour.toString().padStart(2, '0') + ':' + currentMinute.toString().padStart(2, '0');
        
        let heureDebut = document.getElementById('heure_debut');
        let heureFin = document.getElementById('heure_fin');
        
        if(heureDebut && heureDebut.value === '') heureDebut.value = startTime;
        if(heureFin && heureFin.value === '') heureFin.value = endTime;
    }
}
</script>
@endsection