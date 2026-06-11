<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','UniPilot') — UniPilot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: { extend: { colors: {
          primary: { 50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3' }
        }}}
      }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
[x-cloak]{display:none!important}

.sidebar-link{
display:flex;
align-items:center;
gap:.75rem;
padding:.75rem 1rem;
border-radius:.75rem;
font-size:.875rem;
font-weight:500;
color:#6b7280;
transition:.2s;
}

.sidebar-link:hover{
background:#f3f4f6;
color:#111827;
}

.sidebar-link.active{
background:#4f46e5;
color:#fff;
}

.card{
background:#fff;
border:1px solid #e5e7eb;
border-radius:20px;
box-shadow:
0 1px 3px rgba(0,0,0,.04),
0 8px 24px rgba(15,23,42,.04);
}

.btn{
display:inline-flex;
align-items:center;
gap:.5rem;
padding:.5rem 1rem;
font-size:.875rem;
font-weight:500;
border-radius:.75rem;
transition:.2s;
cursor:pointer;
}

.btn-primary{
background:#4f46e5;
color:#fff;
}

.btn-primary:hover{
background:#4338ca;
}

.btn-secondary{
background:#fff;
color:#374151;
border:1px solid #d1d5db;
}

.btn-secondary:hover{
background:#f9fafb;
}

.btn-danger{
background:#dc2626;
color:#fff;
}

.btn-danger:hover{
background:#b91c1c;
}

.btn-success{
background:#16a34a;
color:#fff;
}

.btn-success:hover{
background:#15803d;
}

.btn-sm{
padding:.375rem .75rem;
font-size:.75rem;
}

.form-input{
width:100%;
padding:.625rem .75rem;
border:1px solid #d1d5db;
border-radius:.75rem;
font-size:.875rem;
}

.form-input:focus{
outline:none;
border-color:#4f46e5;
box-shadow:0 0 0 3px rgba(79,70,229,.15);
}

.form-label{
display:block;
margin-bottom:.25rem;
font-size:.75rem;
font-weight:600;
color:#6b7280;
text-transform:uppercase;
letter-spacing:.05em;
}

.badge{
display:inline-flex;
align-items:center;
padding:.25rem .625rem;
border-radius:9999px;
font-size:.75rem;
font-weight:600;
}

.badge-green{
background:#dcfce7;
color:#166534;
}

.badge-red{
background:#fee2e2;
color:#991b1b;
}

.badge-yellow{
background:#fef9c3;
color:#854d0e;
}

.badge-blue{
background:#dbeafe;
color:#1e40af;
}

.badge-purple{
background:#f3e8ff;
color:#7e22ce;
}

.badge-gray{
background:#f3f4f6;
color:#374151;
}

th{
padding:.875rem 1rem;
text-align:left;
font-size:.75rem;
font-weight:600;
text-transform:uppercase;
letter-spacing:.05em;
background:#f9fafb;
border-bottom:1px solid #e5e7eb;
color:#6b7280;
}

td{
padding:.875rem 1rem;
font-size:.875rem;
color:#374151;
border-bottom:1px solid #f3f4f6;
}

tbody tr:hover td{
background:#f9fafb;
}

tbody tr:last-child td{
border-bottom:none;
}
</style>
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f8fafc]" x-data="{ mobileOpen: false }">

<div class="flex h-full">
    {{-- SIDEBAR --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 flex flex-col
                  transition-transform duration-300 lg:translate-x-0"
           :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-gray-200">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm leading-tight">UniPilot</p>
                <p class="text-xs text-gray-500">Administration</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high w-4 text-center"></i> Tableau de bord
            </a>
            <a href="{{ route('admin.etudiants.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.etudiants*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate w-4 text-center"></i> Étudiants
            </a>
            <a href="{{ route('admin.professeurs.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.professeurs*') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user w-4 text-center"></i> Professeurs
            </a>
            <a href="{{ route('admin.programmes.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.programmes*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open w-4 text-center"></i> Programmes & Modules
            </a>
            <a href="{{ route('admin.emploi-du-temps.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.emploi-du-temps*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days w-4 text-center"></i> Emploi du temps
            </a>
            <a href="{{ route('admin.notes.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.notes*') ? 'active' : '' }}">
                <i class="fa-regular fa-clipboard w-4 text-center"></i> Notes
            </a>
            <a href="{{ route('admin.presences.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.presences*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check w-4 text-center"></i> Présences
            </a>
            <a href="{{ route('admin.salles.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.salles*') ? 'active' : '' }}">
                <i class="fa-solid fa-door-open w-4 text-center"></i> Gestion des salles
            </a>
            <a href="{{ route('admin.demandes.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.demandes*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-signature w-4 text-center"></i>
                Demandes admin.
                @php $pendingDemandes = \App\Models\Demande::where('statut','En attente')->count(); @endphp
                @if($pendingDemandes > 0)
                <span class="ml-auto bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $pendingDemandes }}</span>
                @endif
            </a>
            <a href="{{ route('admin.rapports.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.rapports*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar w-4 text-center"></i> Rapports & Analytics
            </a>
            <a href="{{ route('admin.parametres.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.parametres*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear w-4 text-center"></i> Paramètres
            </a>
        </nav>

        {{-- User --}}
        <div class="border-t border-gray-200 px-3 py-3">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ auth()->user()->initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-600 hover:bg-red-50 hover:text-red-700">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-show="mobileOpen" x-cloak @click="mobileOpen=false"></div>

    {{-- MAIN --}}
<div class="flex-1 flex flex-col min-h-screen lg:pl-64">

    {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 h-14 flex items-center px-4 sm:px-6 gap-4">
            <button @click="mobileOpen=!mobileOpen" class="lg:hidden text-gray-500">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 flex-1">
                <i class="fa-solid fa-house text-xs"></i>
                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                <span class="text-gray-900 font-medium">@yield('page-title','Dashboard')</span>
            </div>

            {{-- Search --}}
            <div class="hidden sm:flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-1.5 w-64">
                <i class="fa-solid fa-search text-gray-400 text-sm"></i>
                <input type="text" placeholder="Recherche globale..." class="bg-transparent text-sm outline-none text-gray-600 w-full">
            </div>

            {{-- Notifications --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open=!open" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fa-solid fa-bell text-lg"></i>
                    @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                    @if($unread > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                </button>
                <div x-show="open" x-cloak @click.away="open=false"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 font-semibold text-sm text-gray-900">Notifications</div>
                    <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                        @forelse(auth()->user()->notifications->take(5) as $notif)
                        <div class="px-4 py-3 hover:bg-gray-50 {{ $notif->read_at ? '' : 'bg-primary-50' }}">
                            <p class="text-sm text-gray-800">{{ $notif->data['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <div class="px-4 py-6 text-center text-sm text-gray-400">Aucune notification</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Avatar --}}
            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-xs font-bold cursor-pointer">
                {{ auth()->user()->initials }}
            </div>
        </header>

        {{-- Alerts --}}
        @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
             class="mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('success') }}
            <button @click="show=false" class="ml-auto text-green-400 hover:text-green-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        @endif
        @if($errors->any() || session('error'))
        <div class="mx-6 mt-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
            <div>
                @if(session('error')){{ session('error') }}@endif
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 p-6 lg:p-8 overflow-x-hidden">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>