<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accès suspendu</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 p-6 text-center">
    <div class="max-w-sm space-y-3">
        <h1 class="text-2xl font-bold">Accès suspendu</h1>
        <p class="text-slate-500">L'accès à cette salle est temporairement suspendu. Contactez le support pour plus d'informations.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-slate-500 underline">Se déconnecter</button>
        </form>
    </div>
</body>
</html>