<div class="mx-auto max-w-3xl space-y-5 print:text-black">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline print:hidden">← Devis</a>
            <h1 class="text-2xl font-bold">BEO · {{ $booking->reference }}</h1>
            <p class="text-sm text-slate-500">{{ $booking->event_type }} · {{ $booking->space->name }} · {{ $booking->guests_count }} invités</p>
            <p class="text-sm text-slate-500">{{ $booking->starts_at->format('d/m/Y H:i') }} → {{ $booking->ends_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex gap-2 print:hidden">
            <button onclick="window.print()" class="rounded-lg bg-white/80 px-3 py-2 text-sm shadow-sm dark:bg-slate-800">Imprimer</button>
            <a href="{{ route('bookings.checklist', [$booking, 'entry']) }}" class="rounded-lg bg-amber-500 px-3 py-2 text-sm font-medium text-white">Checklist entrée</a>
            <a href="{{ route('bookings.checklist', [$booking, 'exit']) }}" class="rounded-lg bg-slate-700 px-3 py-2 text-sm font-medium text-white">Checklist sortie</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-white/40 bg-white/70 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr><th class="px-4 py-2">Heure</th><th class="px-4 py-2">Tâche</th><th class="px-4 py-2">Équipe</th></tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $row['time'] }}</td>
                        <td class="px-4 py-3">{{ $row['task'] }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $row['team'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($booking->notes)
        <p class="rounded-xl bg-amber-50 p-4 text-sm dark:bg-amber-900/20"><strong>Notes :</strong> {{ $booking->notes }}</p>
    @endif
</div>
