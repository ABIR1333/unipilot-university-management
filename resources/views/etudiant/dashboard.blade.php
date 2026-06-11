@extends('layouts.etudiant')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<style>
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1); }
    .cours-card { transition: all 0.2s; }
    .cours-card:hover { background-color: #f8fafc; }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
    <p class="text-xs text-gray-500 mt-0.5">Bienvenue, Lucas Petit</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-4 text-white shadow-md">
        <i class="fa-solid fa-chart-line text-lg text-indigo-200 mb-1 block"></i>
        <p class="text-indigo-200 text-[10px] uppercase tracking-wide">Moyenne générale — S4</p>
        <p class="text-2xl font-bold mt-0.5">14.08/20</p>
        <p class="text-indigo-200 text-[10px] mt-0.5">↑ +0.4 vs S3</p>
    </div>
    <div class="stat-card bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <i class="fa-solid fa-calendar-xmark text-lg text-red-500 mb-1 block"></i>
        <p class="text-gray-500 text-[10px] uppercase tracking-wide">Absences ce semestre</p>
        <p class="text-2xl font-bold text-gray-900 mt-0.5">9</p>
        <p class="text-gray-400 text-[10px] mt-0.5">1 non justifiée(s)</p>
    </div>
    <div class="stat-card bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <i class="fa-solid fa-book-open text-lg text-green-500 mb-1 block"></i>
        <p class="text-gray-500 text-[10px] uppercase tracking-wide">Modules en cours</p>
        <p class="text-2xl font-bold text-gray-900 mt-0.5">6</p>
        <p class="text-gray-400 text-[10px] mt-0.5">Semestre 4 — 30 crédits</p>
    </div>
    <div class="stat-card bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <i class="fa-solid fa-file-signature text-lg text-orange-500 mb-1 block"></i>
        <p class="text-gray-500 text-[10px] uppercase tracking-wide">Demandes en attente</p>
        <p class="text-2xl font-bold text-orange-500 mt-0.5">1</p>
        <p class="text-gray-400 text-[10px] mt-0.5">1 en cours de traitement</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 text-sm mb-3">Progression académique</h3>
        <div class="h-56">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <h3 class="font-semibold text-gray-800 text-sm mb-3">Aujourd'hui — {{ \Carbon\Carbon::now()->isoFormat('dddd D MMMM') }}</h3>
        <div class="space-y-2">
            <div class="cours-card p-2.5 rounded-lg bg-gray-50 border-l-3 border-indigo-500">
                <p class="font-semibold text-gray-800 text-sm">Algorithmique</p>
                <p class="text-[11px] text-gray-500 mt-0.5">08:00–10:00 - Amphi A1</p>
                <p class="text-[11px] text-gray-400">Dr. Dubois</p>
                <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">CM</span>
            </div>
            <div class="cours-card p-2.5 rounded-lg bg-gray-50 border-l-3 border-purple-500">
                <p class="font-semibold text-gray-800 text-sm">POO Java</p>
                <p class="text-[11px] text-gray-500 mt-0.5">10:15–12:15 - Salle 201</p>
                <p class="text-[11px] text-gray-400">Prof. Martin</p>
                <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-100 text-purple-700">TD</span>
            </div>
            <div class="cours-card p-2.5 rounded-lg bg-gray-50 border-l-3 border-green-500">
                <p class="font-semibold text-gray-800 text-sm">Base de données</p>
                <p class="text-[11px] text-gray-500 mt-0.5">14:00–16:00 - Labo L3</p>
                <p class="text-[11px] text-gray-400">Dr. Leclerc</p>
                <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">TP</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('evolutionChart')?.getContext('2d');
if(ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['S1', 'S2', 'S3', 'S4'],
            datasets: [{
                label: 'Moyenne',
                data: [12.5, 13.2, 13.7, 14.08],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.05)',
                borderWidth: 2,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointRadius: 3,
                pointHoverRadius: 5,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } }
        }
    });
}
</script>
@endsection