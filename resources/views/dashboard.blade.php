<x-layouts.app>
    @php
        $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold">Tableau de bord</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Prochains événements et options à confirmer.</p>
            </div>
            @if(auth()->user()->role->canSell())
                <a href="{{ route('bookings.create') }}" class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white shadow hover:bg-amber-600">+ Nouvelle réservation</a>
            @endif
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <section class="{{ $card }}">
                <h2 class="mb-3 font-semibold">Prochains événements</h2>
                @forelse($upcoming as $booking)
                    <a href="{{ route('bookings.beo', $booking) }}" class="flex items-center justify-between gap-2 border-b border-slate-200/70 py-2.5 last:border-0 dark:border-slate-800">
                        <span class="text-sm"><strong>{{ $booking->reference }}</strong> · {{ $booking->event_type }} · {{ $booking->space->name }}</span>
                        <span class="whitespace-nowrap text-xs text-slate-500">{{ $booking->starts_at->format('d/m H:i') }}</span>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Aucun événement à venir.</p>
                @endforelse
            </section>

            <section class="{{ $card }}">
                <h2 class="mb-3 font-semibold">Options à confirmer</h2>
                @forelse($expiringOptions as $booking)
                    <a href="{{ route('bookings.quote', $booking) }}" class="flex items-center justify-between gap-2 border-b border-slate-200/70 py-2.5 last:border-0 dark:border-slate-800">
                        <span class="text-sm">{{ $booking->client_name }} · {{ $booking->space->name }}</span>
                        <span class="whitespace-nowrap text-xs text-amber-600">expire {{ $booking->option_expires_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Aucune option en attente.</p>
                @endforelse
            </section>
        </div>
    </div>
</x-layouts.app>
