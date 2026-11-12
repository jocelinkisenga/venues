<x-public-layout title="Demande envoyée">
    <div class="mx-auto max-w-xl px-4 py-16 text-center sm:py-24">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 ring-8 ring-emerald-50/50 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/5">
            <x-ui-icon name="check" class="h-8 w-8" />
        </span>

        <h1 class="mt-6 text-balance text-3xl font-semibold tracking-tight">Votre demande est envoyée</h1>
        <p class="mt-3 text-pretty text-stone-500">
            {{ $booking->venue->name }} a bien reçu votre demande et la confirmera dans les meilleurs délais.
        </p>

        <div class="mt-10 divide-y divide-stone-100 rounded-3xl bg-white p-6 text-left ring-1 ring-stone-900/5 dark:divide-stone-800 dark:bg-stone-900 dark:ring-white/10">
            <div class="flex items-center justify-between py-3 text-sm">
                <span class="text-stone-500">Référence</span>
                <span class="font-mono font-semibold">{{ $booking->reference }}</span>
            </div>
            <div class="flex items-center justify-between py-3 text-sm">
                <span class="text-stone-500">Événement</span>
                <span class="font-medium">{{ $booking->event_type }}</span>
            </div>
            <div class="flex items-center justify-between gap-4 py-3 text-sm">
                <span class="text-stone-500">Date</span>
                <span class="text-right font-medium">{{ $booking->starts_at->format('d/m/Y H:i') }} → {{ $booking->ends_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <p class="mt-4 text-xs leading-relaxed text-stone-400">
            Votre date est bloquée {{ config('venue.option_hold_hours') }} heures. Conservez cette page : elle contient votre lien personnel.
        </p>

        <div class="mt-8 flex flex-col gap-3">
            <a href="{{ $portalUrl }}"
               class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600">
                Voir mon devis et payer l'acompte
            </a>
            <a href="{{ route('explorer') }}" class="text-sm font-medium text-stone-500 transition hover:text-stone-900 dark:hover:text-stone-200">
                Retour aux salles
            </a>
        </div>
    </div>
</x-public-layout>