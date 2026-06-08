<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — UniPilot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="h-full bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 flex items-center justify-center p-4">
<div class="w-full max-w-sm">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 backdrop-blur mb-3">
            <i class="fa-solid fa-graduation-cap text-white text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white">UniPilot</h1>
    </div>
    <div class="bg-white rounded-2xl shadow-2xl p-7">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Mot de passe oublié</h2>
        <p class="text-gray-500 text-sm mb-5">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                Envoyer le lien
            </button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Retour à la connexion
            </a>
        </div>
    </div>
</div>
</body>
</html>
