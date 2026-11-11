<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Invitation · {{ $guest->booking->venue->name }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-amber-50 p-4 text-slate-800">
    @php $booking = $guest->booking; $venue = $booking->venue; @endphp

    <div class="mx-auto max-w-md space-y-5 text-center">
        <div>
            <p class="text-sm text-slate-500">{{ $venue->name }}</p>
            <h1 class="text-2xl font-bold">Bonjour {{ $guest->full_name }}</h1>
            <p class="mt-1">{{ $booking->event_type }} · {{ $booking->starts_at->format('d/m/Y à H:i') }}</p>
        </div>

        @if($guest->attended_at)
            <p class="rounded-xl bg-slate-100 p-4 text-sm">Vous êtes déjà entré(e). Merci de votre présence.</p>
        @elseif($guest->rsvp_status === 'confirmed')
            <div class="space-y-3 rounded-2xl bg-white p-6 shadow-sm">
                <p class="font-semibold text-emerald-600">Présence confirmée{{ $guest->companions ? " · {$guest->companions} accompagnant(s)" : '' }}</p>
                <div class="mx-auto w-56">{!! QrCode::size(224)->margin(1)->generate($guest->token) !!}</div>
                <p class="text-xs text-slate-500">Présentez ce code à l'entrée. Il est personnel.</p>
            </div>
        @endif

        @if(! $guest->attended_at)
            <form method="POST" action="{{ route('rsvp.store', $guest->token) }}" class="space-y-3 rounded-2xl bg-white p-5 text-left shadow-sm">
                @csrf
                <label class="block text-sm font-medium">Serez-vous présent(e) ?</label>
                <div class="grid grid-cols-2 gap-2">
                    <button name="response" value="confirmed" class="rounded-xl bg-emerald-600 py-3 font-semibold text-white">Oui</button>
                    <button name="response" value="declined" class="rounded-xl bg-slate-200 py-3 font-semibold text-slate-700">Non</button>
                </div>
                <label class="block text-sm font-medium">Nombre d'accompagnants</label>
                <input type="number" min="0" max="10" name="companions" value="{{ $guest->companions }}" class="w-full rounded-xl border border-slate-300 px-3 py-2">
                @error('response') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </form>
        @endif
    </div>
</body>
</html>