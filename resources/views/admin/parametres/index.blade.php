@extends('layouts.app')
@section('title','Paramètres')
@section('page-title','Paramètres')

@section('content')
<div class="flex items-center justify-between mb-5">
    <h2 class="text-xl font-bold text-gray-900">Paramètres</h2>
    <p class="text-xs text-gray-400 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'roles' }" class="space-y-5">
    <div class="flex gap-1 border-b border-gray-200">
        @foreach([['roles','Rôles & Utilisateurs'],['permissions','Permissions'],['config','Configuration'],['notifs','Notifications']] as [$id,$label])
        <button @click="tab='{{ $id }}'"
                :class="tab==='{{ $id }}' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2.5 text-sm font-semibold transition-colors -mb-px">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Rôles --}}
    <div x-show="tab==='roles'" class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        @php $roleConfig = [
            'admin'=>['label'=>'Administrateur','desc'=>'Accès complet','color'=>'indigo','icon'=>'fa-shield'],
            'professeur'=>['label'=>'Professeur','desc'=>'Notes, Présences, Cours','color'=>'blue','icon'=>'fa-chalkboard-user'],
            'etudiant'=>['label'=>'Étudiant','desc'=>'Lecture seule + Demandes','color'=>'green','icon'=>'fa-user-graduate'],
        ]; @endphp
        @foreach($roles as $role)
        @php $cfg = $roleConfig[$role->name] ?? ['label'=>$role->name,'desc'=>'','color'=>'gray','icon'=>'fa-user']; @endphp
        <div class="card p-6">
            <div class="w-12 h-12 rounded-xl bg-{{ $cfg['color'] }}-100 flex items-center justify-center mb-4">
                <i class="fa-solid {{ $cfg['icon'] }} text-{{ $cfg['color'] }}-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-lg">{{ $cfg['label'] }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">{{ $role->users->count() }} utilisateurs</p>
            <div class="mt-3 px-3 py-2 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-700">{{ $cfg['desc'] }}</p>
            </div>
            <button class="mt-4 text-sm text-indigo-600 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-pen text-xs"></i> Modifier les droits
            </button>
        </div>
        @endforeach
    </div>

    {{-- Permissions tab --}}
    <div x-show="tab==='permissions'" x-cloak class="card p-6">
        <p class="text-sm text-gray-500">La gestion fine des permissions est disponible via Laravel Spatie Permissions.</p>
        <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach(['Voir étudiants','Créer étudiants','Modifier étudiants','Supprimer étudiants','Voir notes','Modifier notes','Voir présences','Saisir présences','Voir rapports','Exporter PDF','Gérer salles','Gérer programmes'] as $perm)
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fa-solid fa-check-circle text-green-500"></i> {{ $perm }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- Configuration tab --}}
    <div x-show="tab==='config'" x-cloak class="card p-6 max-w-lg">
        <h3 class="font-semibold text-gray-900 mb-4">Configuration générale</h3>
        <div class="space-y-4">
            <div><label class="form-label">Nom de l'université</label>
                <input type="text" value="{{ $config['nom_universite'] }}" class="form-input"></div>
            <div><label class="form-label">Année académique en cours</label>
                <input type="text" value="{{ $config['annee_academique'] }}" class="form-input"></div>
            <div><label class="form-label">Semestre actuel</label>
                <select class="form-input">
                    @for($i=1;$i<=10;$i++)
                    <option {{ $config['semestre_actuel']==$i?'selected':'' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button class="btn-primary">Enregistrer</button>
        </div>
    </div>

    {{-- Notifications tab --}}
    <div x-show="tab==='notifs'" x-cloak class="card p-6 max-w-lg">
        <h3 class="font-semibold text-gray-900 mb-4">Préférences de notifications</h3>
        <div class="space-y-3">
            @foreach(['Nouvelles demandes administratives','Notes saisies par les professeurs','Absences répétées des étudiants','Résultats publiés','Réservations de salles'] as $notif)
            <label class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm text-gray-700">{{ $notif }}</span>
                <div class="relative">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-colors cursor-pointer"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow"></div>
                </div>
            </label>
            @endforeach
        </div>
    </div>
</div>
@endsection
