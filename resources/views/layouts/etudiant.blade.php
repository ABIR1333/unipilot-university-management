<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','UniPilot') — Espace Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            transition: all 0.2s;
        }
        .sidebar-link:hover { background: #f3f4f6; color: #111827; }
        .sidebar-link.active { background: #4f46e5; color: white; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.75rem; font-size: 0.875rem; }
        .form-input:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.15); }
        .form-label { display: block; margin-bottom: 0.25rem; font-size: 0.7rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
        .btn-primary { background: #4f46e5; color: white; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; }
        .btn-primary:hover { background: #4338ca; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f8fafc]" x-data="{ mobileOpen: false }">

<div class="flex h-full">
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 lg:translate-x-0"
           :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-200">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm leading-tight">UniPilot</p>
                <p class="text-xs text-gray-500">Espace Étudiant</p>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
            <a href="{{ route('etudiant.dashboard') }}" class="sidebar-link {{ request()->routeIs('etudiant.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line w-4"></i> Tableau de bord
            </a>
            <a href="{{ route('etudiant.notes.index') }}" class="sidebar-link {{ request()->routeIs('etudiant.notes*') ? 'active' : '' }}">
                <i class="fa-regular fa-clipboard w-4"></i> Mes notes
            </a>
            <a href="{{ route('etudiant.emploi-du-temps.index') }}" class="sidebar-link {{ request()->routeIs('etudiant.emploi-du-temps*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days w-4"></i> Emploi du temps
            </a>
            <a href="{{ route('etudiant.presences.index') }}" class="sidebar-link {{ request()->routeIs('etudiant.presences*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check w-4"></i> Mes présences
            </a>
            <a href="{{ route('etudiant.espace-cours.index') }}" class="sidebar-link {{ request()->routeIs('etudiant.espace-cours*') ? 'active' : '' }}">
                <i class="fa-solid fa-desktop w-4"></i> Espace cours
            </a>
            <a href="{{ route('etudiant.demandes.index') }}" class="sidebar-link {{ request()->routeIs('etudiant.demandes*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-signature w-4"></i> Demandes admin.
            </a>
            <a href="{{ route('etudiant.profil.index') }}" class="sidebar-link {{ request()->routeIs('etudiant.profil*') ? 'active' : '' }}">
                <i class="fa-solid fa-user w-4"></i> Mon profil
            </a>
        </nav>
        <div class="border-t border-gray-200 px-3 py-3">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ auth()->user()->initials ?? substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link w-full text-red-600 hover:bg-red-50 hover:text-red-700">
                    <i class="fa-solid fa-right-from-bracket w-4"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen lg:pl-64">
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 h-14 flex items-center px-4 sm:px-6 gap-4">
            <button @click="mobileOpen=!mobileOpen" class="lg:hidden text-gray-500">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <div class="flex-1">
                <span class="text-gray-900 font-medium">@yield('page-title')</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ auth()->user()->initials ?? substr(auth()->user()->name, 0, 2) }}
                </div>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-6 mt-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
        @endif

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>