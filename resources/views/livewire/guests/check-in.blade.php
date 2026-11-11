@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-3 text-lg dark:border-slate-700 dark:bg-slate-900';
@endphp

<div class="mx-auto max-w-md space-y-5">
    <div class="text-center">
        <h1 class="text-2xl font-bold">Entrée · {{ $booking->reference }}</h1>
        <p class="text-sm text-slate-500">{{ $admitted }} entrée(s) sur {{ $expected }} attendue(s)</p>
    </div>

    @if($result)
        <div class="rounded-2xl p-5 text-center text-lg font-semibold
            {{ $resultType === 'ok' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
            {{ $result }}
        </div>
    @endif

    <form wire:submit="admit" class="space-y-3">
        <input wire:model="code" autofocus autocomplete="off" placeholder="Scanner ou saisir le code du billet"
               class="{{ $field }} w-full text-center">
        @error('code') <p class="text-center text-sm text-red-500">{{ $message }}</p> @enderror
        <button class="w-full rounded-xl bg-amber-500 py-3 font-semibold text-white hover:bg-amber-600">Valider l'entrée</button>
    </form>

    @if($recent->isNotEmpty())
        <div class="rounded-2xl border border-white/40 bg-white/70 p-4 text-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
            <h2 class="mb-2 font-semibold">Dernières entrées</h2>
            @foreach($recent as $g)
                <p class="flex justify-between border-t border-slate-200/70 py-1 first:border-0 dark:border-slate-800">
                    <span>{{ $g->full_name }}</span><span class="text-slate-500">{{ $g->attended_at->format('H:i') }}</span>
                </p>
            @endforeach
        </div>
    @endif
</div>