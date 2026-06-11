@extends('layouts.etudiant')

@section('title', 'Mes notes')
@section('page-title', 'Mes notes')

@section('content')
<style>
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1); }
    .table-row:hover { background-color: #f8fafc; }
    .badge-mention { padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.65rem; font-weight: 600; }
    .badge-excellent { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
    .badge-bien { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
    .badge-assez-bien { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }
    .badge-passable { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
    th { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    td { font-size: 0.8rem; }
    .module-name { font-size: 0.85rem; font-weight: 600; }
</style>

<div class="mb-5">
    <h1 class="text-2xl font-bold text-gray-900">Mes notes</h1>
    <p class="text-xs text-gray-500 mt-0.5">Consultez vos résultats académiques du semestre 4</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat-card bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-4 text-white shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-200 text-[10px] uppercase tracking-wide">Moyenne pondérée</p>
                <p class="text-3xl font-bold mt-1">14.08<small class="text-sm">/20</small></p>
                <p class="text-indigo-200 text-[10px] mt-0.5">Semestre 4 — Licence Info</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-white text-sm"></i>
            </div>
        </div>
    </div>
    <div class="stat-card bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fa-solid fa-check text-green-600 text-sm"></i>
            </div>
            <div>
                <p class="text-gray-400 text-[10px] uppercase tracking-wide">Modules validés</p>
                <p class="text-xl font-bold text-gray-900">6/6</p>
                <p class="text-gray-400 text-[10px]">30 crédits obtenus</p>
            </div>
        </div>
    </div>
    <div class="stat-card bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center">
                <i class="fa-solid fa-trophy text-amber-600 text-sm"></i>
            </div>
            <div>
                <p class="text-gray-400 text-[10px] uppercase tracking-wide">Meilleure note</p>
                <p class="text-xl font-bold text-gray-900">16.1<small class="text-xs">/20</small></p>
                <p class="text-gray-400 text-[10px]">Base de données</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <h3 class="font-semibold text-gray-800 text-sm">
            <i class="fa-solid fa-table-list mr-1.5 text-indigo-500 text-xs"></i>
            Résultats détaillés — Semestre 4
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-3 py-2 text-left text-[11px]">Module</th>
                    <th class="px-2 py-2 text-center text-[11px] w-14">CC1</th>
                    <th class="px-2 py-2 text-center text-[11px] w-14">CC2</th>
                    <th class="px-2 py-2 text-center text-[11px] w-16">Examen</th>
                    <th class="px-2 py-2 text-center text-[11px] w-16">Finale</th>
                    <th class="px-2 py-2 text-center text-[11px] w-16">Crédits</th>
                    <th class="px-2 py-2 text-center text-[11px] w-24">Mention</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2 font-semibold text-gray-800 text-[13px]">Algorithmique</td>
                    <td class="px-2 py-2 text-center text-[13px]">15</td>
                    <td class="px-2 py-2 text-center text-[13px]">14</td>
                    <td class="px-2 py-2 text-center text-[13px]">16.5</td>
                    <td class="px-2 py-2 text-center font-bold text-indigo-600 text-[13px]">15.4</td>
                    <td class="px-2 py-2 text-center text-[11px] text-gray-500">6 ECTS</td>
                    <td class="px-2 py-2 text-center"><span class="badge-mention badge-bien">Bien</span></td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2 font-semibold text-gray-800 text-[13px]">POO Java</td>
                    <td class="px-2 py-2 text-center text-[13px]">12</td>
                    <td class="px-2 py-2 text-center text-[13px]">13</td>
                    <td class="px-2 py-2 text-center text-[13px]">14</td>
                    <td class="px-2 py-2 text-center font-bold text-indigo-600 text-[13px]">13.2</td>
                    <td class="px-2 py-2 text-center text-[11px] text-gray-500">5 ECTS</td>
                    <td class="px-2 py-2 text-center"><span class="badge-mention badge-assez-bien">Assez Bien</span></td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2 font-semibold text-gray-800 text-[13px]">Base de données</td>
                    <td class="px-2 py-2 text-center text-[13px]">16</td>
                    <td class="px-2 py-2 text-center text-[13px]">17</td>
                    <td class="px-2 py-2 text-center text-[13px]">15.5</td>
                    <td class="px-2 py-2 text-center font-bold text-indigo-600 text-[13px]">16.1</td>
                    <td class="px-2 py-2 text-center text-[11px] text-gray-500">5 ECTS</td>
                    <td class="px-2 py-2 text-center"><span class="badge-mention badge-bien">Bien</span></td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2 font-semibold text-gray-800 text-[13px]">Réseaux</td>
                    <td class="px-2 py-2 text-center text-[13px]">11</td>
                    <td class="px-2 py-2 text-center text-[13px]">10</td>
                    <td class="px-2 py-2 text-center text-[13px]">12</td>
                    <td class="px-2 py-2 text-center font-bold text-indigo-600 text-[13px]">11.2</td>
                    <td class="px-2 py-2 text-center text-[11px] text-gray-500">4 ECTS</td>
                    <td class="px-2 py-2 text-center"><span class="badge-mention badge-assez-bien">Assez Bien</span></td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2 font-semibold text-gray-800 text-[13px]">Mathématiques</td>
                    <td class="px-2 py-2 text-center text-[13px]">14</td>
                    <td class="px-2 py-2 text-center text-[13px]">13</td>
                    <td class="px-2 py-2 text-center text-[13px]">15</td>
                    <td class="px-2 py-2 text-center font-bold text-indigo-600 text-[13px]">14.2</td>
                    <td class="px-2 py-2 text-center text-[11px] text-gray-500">6 ECTS</td>
                    <td class="px-2 py-2 text-center"><span class="badge-mention badge-assez-bien">Assez Bien</span></td>
                </tr>
                <tr class="table-row">
                    <td class="px-3 py-2 font-semibold text-gray-800 text-[13px]">Systèmes d'exploitation</td>
                    <td class="px-2 py-2 text-center text-[13px]">13</td>
                    <td class="px-2 py-2 text-center text-[13px]">14</td>
                    <td class="px-2 py-2 text-center text-[13px]">13</td>
                    <td class="px-2 py-2 text-center font-bold text-indigo-600 text-[13px]">13.4</td>
                    <td class="px-2 py-2 text-center text-[11px] text-gray-500">4 ECTS</td>
                    <td class="px-2 py-2 text-center"><span class="badge-mention badge-assez-bien">Assez Bien</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5 p-3 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center">
            <i class="fa-solid fa-calculator text-indigo-600 text-xs"></i>
        </div>
        <div>
            <p class="text-[10px] text-gray-600">Moyenne générale du semestre</p>
            <p class="text-lg font-bold text-indigo-700">14.08 <span class="text-[10px]">/20</span></p>
        </div>
        <div class="ml-auto text-right">
            <p class="text-[10px] text-gray-500">Crédits validés</p>
            <p class="text-base font-bold text-green-600">30/30 ECTS</p>
        </div>
    </div>
</div>
@endsection