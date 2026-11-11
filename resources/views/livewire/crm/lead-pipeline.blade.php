@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
@endphp

<div class="space-y-5">
    <div>
        <h1 class="text-2xl font-bold">Pipeline commercial</h1>
        <p class="text-sm text-slate-500">Du premier contact à la signature.</p>
    </div>

    <form wire:submit="create" class="grid gap-3 rounded-2xl border border-white/40 bg-white/70 p-4 backdrop-blur-xl sm:grid-cols-3 md:grid-cols-6 dark:border-slate-800 dark:bg-slate-900/60">
        <input wire:model="full_name" placeholder="Nom du prospect" class="{{ $field }} md:col-span-2">
        <input wire:model="phone" placeholder="WhatsApp" class="{{ $field }}">
        <select wire:model="event_type" class="{{ $field }}">
            {{-- @foreach(config('venue.event_types') as $type)<option>{{ $type }}</option>@endforeach --}}
            @foreach($eventTypes as $type)<option>{{ $type }}</option>@endforeach
        </select>
        <input type="number" wire:model="expected_guests" placeholder="Invités" class="{{ $field }}">
        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Ajouter</button>
        @error('full_name') <p class="text-sm text-red-500 sm:col-span-3 md:col-span-6">{{ $message }}</p> @enderror
    </form>

    <div class="flex gap-4 overflow-x-auto pb-4">
        @foreach($columns as $column)
            <div class="w-64 shrink-0 rounded-2xl bg-white/60 p-3 backdrop-blur dark:bg-slate-900/50">
                <h2 class="mb-3 flex justify-between text-sm font-semibold">
                    {{ $column->label() }}
                    <span class="text-slate-400">{{ ($leads[$column->value] ?? collect())->count() }}</span>
                </h2>
                <div class="space-y-2">
                    @foreach($leads[$column->value] ?? [] as $lead)
                        <div class="rounded-xl bg-white p-3 shadow-sm dark:bg-slate-800">
                            <p class="font-medium">{{ $lead->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $lead->event_type }} @if($lead->expected_guests)· {{ $lead->expected_guests }} inv.@endif</p>
                            <select wire:change="move({{ $lead->id }}, $event.target.value)" class="mt-2 w-full rounded-lg border-slate-300 bg-transparent text-xs dark:border-slate-600">
                                @foreach($columns as $option)
                                    <option value="{{ $option->value }}" @selected($option->value === $lead->status->value)>{{ $option->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
