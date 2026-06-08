@extends('layouts.app')
@section('title','Emploi du temps')
@section('page-title','Emploi du temps')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Emploi du temps</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.emploi-du-temps.export-pdf',['annee'=>$annee,'semestre'=>$semestre]) }}" class="btn-secondary">
            <i class="fa-solid fa-download"></i> Exporter PDF
        </a>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Ajouter
        </button>
    </div>
</div>

{{-- Week navigation --}}
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-3">
        <button class="p-1.5 rounded-lg border border-gray-300 hover:bg-gray-50">
            <i class="fa-solid fa-chevron-left text-sm text-gray-600"></i>
        </button>
        <span class="font-semibold text-gray-900">Semaine du 2 au 6 Juin 2025</span>
        <button class="p-1.5 rounded-lg border border-gray-300 hover:bg-gray-50">
            <i class="fa-solid fa-chevron-right text-sm text-gray-600"></i>
        </button>
    </div>
    <div class="flex gap-2">
        @foreach(['CM','TD','TP'] as $type)
        <span class="badge {{ $type==='CM'?'badge-blue':($type==='TD'?'badge-purple':'badge-green') }}">{{ $type }}</span>
        @endforeach
    </div>
</div>

{{-- Timetable grid --}}
<div class="card overflow-hidden">
    <div class="grid grid-cols-5 divide-x divide-gray-200">
        @foreach($jours as $jour)
        <div>
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider text-center">{{ strtoupper($jour) }}</p>
            </div>
            <div class="p-2 space-y-2 min-h-64">
                @foreach($emplois->get($jour, collect()) as $seance)
                <div class="rounded-xl p-3 {{ $seance->bg_color }} border cursor-pointer hover:opacity-90 transition-opacity"
                     onclick="confirmDelete({{ $seance->id }})">
                    <p class="font-semibold text-sm {{ $seance->text_color }}">{{ $seance->module->nom }}</p>
                    <p class="text-xs {{ $seance->text_color }} opacity-80 mt-0.5">
                        {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}–{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                    </p>
                    @if($seance->salle)
                    <p class="text-xs {{ $seance->text_color }} opacity-70">{{ $seance->salle->nom }}</p>
                    @endif
                    <span class="inline-block text-xs mt-1 px-1.5 py-0.5 rounded bg-white/50 {{ $seance->text_color }} font-medium">
                        {{ $seance->type_seance }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Add Modal --}}
<div id="addModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Ajouter une séance</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.emploi-du-temps.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="form-label">Module *</label>
                    <select name="module_id" required class="form-input">
                        <option value="">Sélectionner</option>
                        @foreach($modules as $m)
                        <option value="{{ $m->id }}">{{ $m->nom }} ({{ $m->programme->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Professeur *</label>
                    <select name="professeur_id" required class="form-input">
                        <option value="">Sélectionner</option>
                        @foreach($professeurs as $p)
                        <option value="{{ $p->id }}">{{ $p->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Salle</label>
                    <select name="salle_id" class="form-input">
                        <option value="">Aucune</option>
                        @foreach($salles as $s)
                        <option value="{{ $s->id }}">{{ $s->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Jour *</label>
                    <select name="jour" required class="form-input">
                        @foreach($jours as $j)
                        <option value="{{ $j }}">{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Type *</label>
                    <select name="type_seance" required class="form-input">
                        @foreach(['CM','TD','TP'] as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Heure début *</label>
                    <input type="time" name="heure_debut" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Heure fin *</label>
                    <input type="time" name="heure_fin" required class="form-input">
                </div>
                <input type="hidden" name="annee_academique" value="{{ $annee }}">
                <input type="hidden" name="semestre" value="{{ $semestre }}">
            </div>
            <div class="flex justify-end gap-3 mt-5">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Ajouter</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete form (hidden) --}}
<form id="deleteForm" method="POST" style="display:none">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete(id) {
    if (!confirm('Supprimer cette séance ?')) return;
    const form = document.getElementById('deleteForm');
    form.action = '/admin/emploi-du-temps/' + id;
    form.submit();
}
</script>
@endpush
@endsection
