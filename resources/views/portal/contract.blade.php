<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Contrat {{ $contract->number }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-amber-50 p-4 text-slate-800">
    <div class="mx-auto max-w-2xl space-y-5">
        @if(session('success'))
            <p class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('success') }}</p>
        @endif

        <div class="rounded-2xl border border-white/50 bg-white/80 p-6 text-sm shadow-sm">
            {!! $contract->body !!}
        </div>

        @if($contract->status === 'signed')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-800">
                Contrat signé par <strong>{{ $contract->signed_name }}</strong> le {{ $contract->signed_at->format('d/m/Y à H:i') }}.
            </div>
        @else
            <form method="POST" action="{{ request()->fullUrl() }}" class="space-y-3 rounded-2xl border border-white/50 bg-white/80 p-5 shadow-sm">
                @csrf
                <label class="block text-sm font-medium">Votre nom complet, pour signer</label>
                <input name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2">
                @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                <label class="flex items-start gap-2 text-sm">
                    <input type="checkbox" name="accept" value="1" class="mt-1">
                    <span>J'ai lu le contrat et j'en accepte les conditions.</span>
                </label>
                @error('accept') <p class="text-sm text-red-500">Veuillez accepter les conditions.</p> @enderror

                <button class="w-full rounded-xl bg-amber-500 py-3 font-semibold text-white shadow hover:bg-amber-600">Signer le contrat</button>
            </form>
        @endif
    </div>
</body>
</html>