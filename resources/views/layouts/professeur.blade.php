<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','UniPilot') — Espace Professeur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: { extend: { colors: {
          primary: { 50:'#eff6ff',100:'#dbeafe',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8' }
        }}}
      }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        
        .card {
            @apply bg-white rounded-3xl border border-gray-200 shadow-sm;
        }
        
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-2;
        }
        
        .form-input {
            @apply w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500;
        }
        
        .btn-primary {
            @apply inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-2xl font-medium hover:bg-blue-700 transition;
        }
        
        .btn-sm {
            @apply px-4 py-2 text-sm rounded-xl;
        }
        
        .badge {
            @apply px-3 py-1 rounded-full text-xs font-semibold;
        }
        
        .badge-green {
            @apply bg-green-100 text-green-700;
        }
        
        .badge-red {
            @apply bg-red-100 text-red-700;
        }
        
        .badge-yellow {
            @apply bg-yellow-100 text-yellow-700;
        }
        
        .badge-blue {
            @apply bg-blue-100 text-blue-700;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50" x-data="{ mobileOpen: false }">
<div class="flex h-full">
    {{-- SIDEBAR --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-60 bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 lg:translate-x-0"
           :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-200">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm leading-tight">UniPilot</p>
                <p class="text-xs text-gray-500">Espace Professeur</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
            <a href="{{ route('admin.professeur.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-house w-4 text-center"></i> Tableau de bord
            </a>
            
            <a href="{{ route('admin.professeur.notes.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.notes*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-regular fa-clipboard w-4 text-center"></i> Saisie des notes
            </a>
            
            <a href="{{ route('admin.professeur.presences.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.presences*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-calendar-check w-4 text-center"></i> Suivi des présences
            </a>
            
            <a href="{{ route('admin.professeur.journal.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.journal*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-book-open w-4 text-center"></i> Journal pédagogique
            </a>
            
            <a href="{{ route('admin.professeur.espace-cours.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.espace-cours*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-desktop w-4 text-center"></i> Espace cours
            </a>
            
            <a href="{{ route('admin.professeur.reservations.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.reservations*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-door-open w-4 text-center"></i> Réservation de salle
            </a>
            
            <a href="{{ route('admin.professeur.emploi-du-temps.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.emploi-du-temps*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-calendar-days w-4 text-center"></i> Mon emploi du temps
            </a>
            
            <a href="{{ route('admin.professeur.demandes.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.demandes*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-file-signature w-4 text-center"></i> Demandes admin.
            </a>
            
            <a href="{{ route('admin.professeur.profil.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.professeur.profil*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fa-solid fa-user w-4 text-center"></i> Mon profil
            </a>
        </nav>

        {{-- User --}}
        <div class="border-t border-gray-200 px-3 py-3">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ auth()->user()->initials ?? substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 w-full">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-full lg:pl-60">
        {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 h-14 flex items-center px-4 sm:px-6 gap-4">
            <button @click="mobileOpen=!mobileOpen" class="lg:hidden text-gray-500">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <div class="flex items-center gap-2 text-sm text-gray-500 flex-1">
                <i class="fa-solid fa-house text-xs"></i>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                <span class="text-gray-900 font-medium">@yield('page-title')</span>
            </div>
            <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                <span>{{ auth()->user()->name }}</span>
                @if(auth()->user()->professeur?->specialite)
                <span class="text-gray-400">·</span>
                <span class="text-gray-500 text-xs">{{ Str::limit(auth()->user()->professeur->specialite, 20) }}</span>
                @endif
            </div>
            <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-bell text-lg"></i>
            </button>
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                {{ auth()->user()->initials ?? substr(auth()->user()->name, 0, 2) }}
            </div>
        </header>

        {{-- Alerts --}}
        @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
             class="mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('success') }}
            <button @click="show=false" class="ml-auto text-green-400"><i class="fa-solid fa-xmark"></i></button>
        </div>
        @endif
        @if($errors->any())
        <div class="mx-6 mt-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
            <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        </div>
        @endif

        <main class="flex-1 px-4 sm:px-6 py-5 pb-10">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>