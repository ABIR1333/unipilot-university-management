<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — UniPilot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 flex items-center justify-center p-4">
<div class="w-full max-w-sm">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 backdrop-blur mb-3">
            <i class="fa-solid fa-graduation-cap text-white text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white">UniPilot</h1>
        <p class="text-indigo-300 text-sm mt-1">Système de gestion universitaire</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-7">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Connexion</h2>
        <p class="text-gray-500 text-sm mb-5">Accédez à votre espace</p>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Adresse email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="votre@email.fr">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Mot de passe</label>
                <div class="relative" x-data="{show:false}">
                    <input :type="show?'text':'password'" name="password" required
                           class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="••••••••">
                    <button type="button" @click="show=!show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i :class="show?'fa-eye-slash':'fa-eye'" class="fa-solid text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-600">Se souvenir</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Mot de passe oublié ?</a>
            </div>
            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm mt-1">
                Se connecter
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 text-center font-medium mb-2">Comptes de démonstration</p>
            <div class="space-y-1 text-xs text-gray-500">
                <div class="flex justify-between bg-gray-50 rounded px-2.5 py-1.5">
                    <span class="font-medium text-indigo-600">Admin</span>
                    <span>admin@upp.fr / password</span>
                </div>
                <div class="flex justify-between bg-gray-50 rounded px-2.5 py-1.5">
                    <span class="font-medium text-purple-600">Professeur</span>
                    <span>m.dubois@upp.fr / password</span>
                </div>
                <div class="flex justify-between bg-gray-50 rounded px-2.5 py-1.5">
                    <span class="font-medium text-green-600">Étudiant</span>
                    <span>l.petit@etu.upp.fr / password</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
