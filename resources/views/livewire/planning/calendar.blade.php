@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
@endphp

<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <button wire:click="previous" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">←</button>
            <h1 class="min-w-40 text-center text-xl font-bold capitalize">{{ $label }}</h1>
            <button wire:click="next" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">→</button>
        </div>
        <select wire:model.live="spaceId" class="{{ $field }}">
            <option value="">Tous les espaces</option>
            @foreach($spaces as $space)
                <option value="{{ $space->id }}">{{ $space->name }}</option>
            @endforeach
        </select>
    </div>

    @error('calendar')
        <p class="rounded-xl bg-red-50 p-3 text-sm text-red-600 dark:bg-red-900/30">{{ $message }}</p>
    @enderror

    <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-slate-500">
        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
            <div>{{ $dayName }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-1">
        @foreach($days as $key => $day)
            <div wire:key="day-{{ $key }}"
                 @dragover.prevent
                 @drop.prevent="$wire.moveBooking(Number($event.dataTransfer.getData('text/plain')), '{{ $key }}')"
                 class="min-h-24 rounded-lg border border-white/50 p-1 text-xs
                        {{ $day['inMonth'] ? 'bg-white/70 dark:bg-slate-900/60' : 'bg-slate-100/50 text-slate-400 dark:bg-slate-900/20' }}
                        {{ $day['date']->isToday() ? 'ring-2 ring-amber-400' : '' }}">
                <div class="mb-1 font-semibold">{{ $day['date']->day }}</div>
                @foreach($day['bookings'] as $booking)
                    <div wire:key="b-{{ $booking->id }}-{{ $key }}"
                         draggable="true"
                         @dragstart="$event.dataTransfer.setData('text/plain', '{{ $booking->id }}')"
                         title="{{ $booking->client_name }} · {{ $booking->reference }}"
                         class="mb-1 cursor-move truncate rounded px-1 py-0.5 text-white
                                {{ $booking->status->value === 'option' ? 'bg-amber-500' : 'bg-emerald-600' }}">
                        <a href="{{ route('bookings.quote', $booking) }}" class="block truncate">
                            {{ $booking->starts_at->format('H:i') }} {{ $booking->space->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="flex flex-wrap gap-4 text-xs text-slate-500">
        <span><span class="mr-1 inline-block h-3 w-3 rounded bg-amber-500"></span>Option</span>
        <span><span class="mr-1 inline-block h-3 w-3 rounded bg-emerald-600"></span>Confirmé / en cours</span>
        <span>Glissez une réservation sur un autre jour pour la déplacer. Le nettoyage est vérifié automatiquement.</span>
    </div>
</div>
