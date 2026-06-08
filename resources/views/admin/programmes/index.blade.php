@extends('layouts.app')
@section('title','Programmes & Modules')
@section('page-title','Programmes & Modules')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Programmes & Modules</h2>
        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1"><i class="fa-solid fa-rotate text-xs"></i> Dernière mise à jour : il y a 2 min</p>
    </div>
    <button onclick="document.getElementById('addProgrammeModal').classList.remove('hidden')" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter
    </button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-5" x-data="{ selectedId: {{ $selectedProgramme?->id ?? 'null' }} }">
    {{-- Programmes list --}}
    <div>
        <h3 class="font-semibold text-gray-700 mb-3">Programmes ({{ $programmes->count() }})</h3>
        <div class="space-y-3">
            @foreach($programmes as $programme)
            <div class="card p-4 cursor-pointer transition-all hover:border-indigo-300"
                 :class="selectedId === {{ $programme->id }} ? 'border-indigo-500 bg-indigo-50' : ''"
                 @click="selectedId = {{ $programme->id }}; loadModules({{ $programme->id }})">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $programme->nom }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $programme->code }} · {{ $programme->duree_annees }} ans</p>
                        <div class="flex gap-3 mt-2 text-xs text-gray-500">
                           <span>
    <i class="fa-solid fa-users text-gray-400"></i>
    {{ $programme->etudiants_count }} étudiants
</span>

<span>
    <i class="fa-solid fa-book text-gray-400"></i>
    {{ $programme->modules_count }} modules
</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-blue">{{ $programme->type }}</span>
                        <form method="POST" action="{{ route('admin.programmes.destroy',$programme) }}"
                              onsubmit="return confirm('Supprimer ce programme ?')">
                            @csrf @method('DELETE')
                            <button class="p-1 text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Modules panel --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700" id="modulesTitle">
                Modules — {{ $selectedProgramme?->nom ?? 'Sélectionner un programme' }}
            </h3>
            <button onclick="document.getElementById('addModuleModal').classList.remove('hidden')"
                    class="btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Ajouter
            </button>
        </div>
        <div id="modulesGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($modules as $i => $module)
            <div class="card p-4">
                <div class="flex items-start justify-between mb-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                        M{{ $i+1 }}
                    </span>
                    <div class="flex gap-1">
                        <button class="p-1 text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fa-solid fa-ellipsis text-xs"></i>
                        </button>
                    </div>
                </div>
                <p class="font-semibold text-gray-900 text-sm">{{ $module->nom }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Semestre {{ $module->semestre_type }} · {{ $module->heures }}h</p>
                @php $prof = $module->professeurs->first(); @endphp
                @if($prof)
                <p class="text-xs text-gray-400 mt-1">Prof. assigné : {{ $prof->nom }}</p>
                @else
                <p class="text-xs text-red-400 mt-1">Aucun prof. assigné</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Add Programme Modal --}}
<div id="addProgrammeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Nouveau programme</h3>
            <button onclick="document.getElementById('addProgrammeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.programmes.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="form-label">Nom *</label><input type="text" name="nom" required class="form-input" placeholder="Licence Informatique"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Code *</label><input type="text" name="code" required class="form-input" placeholder="LINF"></div>
                    <div><label class="form-label">Durée (ans) *</label><input type="number" name="duree_annees" required min="1" max="8" class="form-input" value="3"></div>
                </div>
                <div><label class="form-label">Type *</label>
                    <select name="type" required class="form-input">
                        @foreach(['Licence','Master','DUT','BTS','Doctorat'] as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addProgrammeModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Créer</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add Module Modal --}}
<div id="addModuleModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Nouveau module</h3>
            <button onclick="document.getElementById('addModuleModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.modules.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="form-label">Programme *</label>
                    <select name="programme_id" required class="form-input" id="moduleProgrammeSelect">
                        @foreach($programmes as $p)
                        <option value="{{ $p->id }}" {{ $selectedProgramme?->id==$p->id?'selected':'' }}>{{ $p->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Nom *</label><input type="text" name="nom" required class="form-input"></div>
                    <div><label class="form-label">Code</label><input type="text" name="code" class="form-input"></div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div><label class="form-label">Semestre</label>
                        <select name="semestre_type" class="form-input">
                            <option value="Impair">Impair</option>
                            <option value="Pair">Pair</option>
                        </select>
                    </div>
                    <div><label class="form-label">Heures</label><input type="number" name="heures" min="1" value="20" class="form-input"></div>
                    <div><label class="form-label">Crédits</label><input type="number" name="credits" min="1" value="3" class="form-input"></div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addModuleModal').classList.add('hidden')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Créer</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
async function loadModules(programmeId) {
    const res = await fetch(`/admin/programmes/${programmeId}/modules`);
    const data = await res.json();
    const grid = document.getElementById('modulesGrid');
    document.getElementById('modulesTitle').textContent = 'Modules — ' + data.programme.nom;
    document.getElementById('moduleProgrammeSelect').value = programmeId;
    if (!data.modules.length) { grid.innerHTML = '<p class="text-gray-400 text-sm col-span-2">Aucun module</p>'; return; }
    grid.innerHTML = data.modules.map((m,i) => `
        <div class="card p-4">
            <div class="flex items-start justify-between mb-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">M${i+1}</span>
            </div>
            <p class="font-semibold text-gray-900 text-sm">${m.nom}</p>
            <p class="text-xs text-gray-500 mt-0.5">Semestre ${m.semestre_type} · ${m.heures}h</p>
        </div>
    `).join('');
}
</script>
@endpush
@endsection
