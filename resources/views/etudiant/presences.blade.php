@extends('layouts.etudiant')

@section('title', 'Mes présences')
@section('page-title', 'Mes présences')

@section('content')
<style>
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1); }
    .table-row:hover { background-color: #f8fafc; }
    .progress-bar { transition: width 0.5s ease; }
    .badge-present { background: #dcfce7; color: #166534; }
    .badge-absent { background: #fee2e2; color: #991b1b; }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mes présences</h1>
    <p class="text-xs text-gray-500 mt-0.5">Suivi de votre assiduité — Semestre 4</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
    <div class="stat-card bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <i class="fa-solid fa-calendar-xmark text-2xl text-red-200 mb-2 block"></i>
                <p class="text-red-200 text-[10px] uppercase tracking-wide">Total absences</p>
                <p class="text-4xl font-bold mt-1">9</p>
                <p class="text-red-200 text-[10px] mt-0.5">Sur 120 séances</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-white text-xl"></i>
            </div>
        </div>
    </div>
    <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <i class="fa-solid fa-calendar-check text-2xl text-green-200 mb-2 block"></i>
                <p class="text-green-200 text-[10px] uppercase tracking-wide">Taux de présence</p>
                <p class="text-4xl font-bold mt-1">92.5%</p>
                <p class="text-green-200 text-[10px] mt-0.5">111/120 séances</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-check-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <h3 class="font-semibold text-gray-800 text-sm">
            <i class="fa-solid fa-table-list mr-1.5 text-indigo-500 text-xs"></i>
            Absences par module
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-500 uppercase">MODULE</th>
                    <th class="px-2 py-2 text-center text-[10px] font-semibold text-gray-500 uppercase w-20">PRÉSENCES</th>
                    <th class="px-2 py-2 text-center text-[10px] font-semibold text-gray-500 uppercase w-16">ABSENCES</th>
                    <th class="px-2 py-2 text-center text-[10px] font-semibold text-gray-500 uppercase w-16">JUSTIFIÉES</th>
                    <th class="px-2 py-2 text-center text-[10px] font-semibold text-gray-500 uppercase w-20">TAUX DE PRÉSENCE</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2.5 font-semibold text-gray-800 text-sm">Algorithmique</td>
                    <td class="px-2 py-2.5 text-center text-sm">22/24</td>
                    <td class="px-2 py-2.5 text-center text-sm text-red-600">2</td>
                    <td class="px-2 py-2.5 text-center text-sm">2</td>
                    <td class="px-2 py-2.5 text-center">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="progress-bar h-full bg-green-500 rounded-full" style="width: 91.7%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">91.7%</span>
                        </div>
                    </td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2.5 font-semibold text-gray-800 text-sm">POO Java</td>
                    <td class="px-2 py-2.5 text-center text-sm">24/24</td>
                    <td class="px-2 py-2.5 text-center text-sm text-red-600">0</td>
                    <td class="px-2 py-2.5 text-center text-sm">0</td>
                    <td class="px-2 py-2.5 text-center">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="progress-bar h-full bg-green-500 rounded-full" style="width: 100%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">100%</span>
                        </div>
                    </td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2.5 font-semibold text-gray-800 text-sm">Base de données</td>
                    <td class="px-2 py-2.5 text-center text-sm">23/24</td>
                    <td class="px-2 py-2.5 text-center text-sm text-red-600">1</td>
                    <td class="px-2 py-2.5 text-center text-sm">1</td>
                    <td class="px-2 py-2.5 text-center">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="progress-bar h-full bg-green-500 rounded-full" style="width: 95.8%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">95.8%</span>
                        </div>
                    </td>
                </tr>
                <tr class="table-row border-b border-gray-100">
                    <td class="px-3 py-2.5 font-semibold text-gray-800 text-sm">Réseaux</td>
                    <td class="px-2 py-2.5 text-center text-sm">20/24</td>
                    <td class="px-2 py-2.5 text-center text-sm text-red-600">4</td>
                    <td class="px-2 py-2.5 text-center text-sm">3</td>
                    <td class="px-2 py-2.5 text-center">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="progress-bar h-full bg-yellow-500 rounded-full" style="width: 83.3%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">83.3%</span>
                        </div>
                    </td>
                </tr>
                <tr class="table-row">
                    <td class="px-3 py-2.5 font-semibold text-gray-800 text-sm">Mathématiques</td>
                    <td class="px-2 py-2.5 text-center text-sm">22/24</td>
                    <td class="px-2 py-2.5 text-center text-sm text-red-600">2</td>
                    <td class="px-2 py-2.5 text-center text-sm">2</td>
                    <td class="px-2 py-2.5 text-center">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="progress-bar h-full bg-green-500 rounded-full" style="width: 91.7%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">91.7%</span>
                        </div>
                    </td>
                 </tr>
            </tbody>
         </table>
    </div>
</div>

<div class="mt-5 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center">
                <i class="fa-solid fa-chart-simple text-indigo-600 text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-500">Taux de présence global</p>
                <p class="text-xl font-bold text-indigo-700">92.5%</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-gray-500">Moyenne de la promotion</p>
            <p class="text-lg font-bold text-gray-700">88.3%</p>
        </div>
    </div>
</div>

<div class="mt-4 flex justify-between items-center p-3 bg-gray-50 rounded-lg">
    <div class="flex items-center gap-3 text-[10px] text-gray-500">
        <i class="fa-regular fa-circle-check text-green-500"></i>
        <span>Absence justifiée = document fourni</span>
    </div>
    <div class="text-[9px] text-gray-400">
        Dernière mise à jour : {{ now()->format('d/m/Y') }}
    </div>
</div>
@endsection