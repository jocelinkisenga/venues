@php
    $inputBase = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500';
    $labelBase = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300';

    // Onglets : sections dynamiques + informations personnelles
    $tabs = collect($sections)->map(fn ($s) => $s['label'])->put('personal', 'Personal')->all();
@endphp

<div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6" x-data="{ tab: 'income' }">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Edit tax profile</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your information. Your recommendations are recalculated when you save.</p>
        </div>

        @if ($tier)
            <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                Recommended tier: {{ $tier }}
            </span>
        @endif
    </div>

    {{-- FEEDBACK --}}
    @if (session('message'))
        <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('message') }}
        </div>
    @endif

    @error('save_error')
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    @if ($errors->any() && ! $errors->has('save_error'))
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            Some fields need your attention. Tabs marked with a red dot contain errors.
        </div>
    @endif

    <form wire:submit="save" novalidate>

        {{-- TABS --}}
        <div class="-mx-4 mb-6 overflow-x-auto px-4 sm:mx-0 sm:px-0">
            <div role="tablist" class="flex w-max min-w-full gap-1 rounded-xl border border-gray-200 bg-gray-50 p-1 dark:border-gray-700/70 dark:bg-gray-800/60">
                @foreach ($tabs as $key => $label)
                    <button type="button" role="tab" id="tab-{{ $key }}"
                        @click="tab = '{{ $key }}'"
                        :aria-selected="tab === '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-900 dark:text-white'
                            : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
                        class="relative whitespace-nowrap rounded-lg px-3.5 py-2 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/40">
                        {{ $label }}
                        @if ($errors->has("form.$key.*"))
                            <span class="absolute right-1.5 top-1.5 h-1.5 w-1.5 rounded-full bg-red-500" aria-label="Contains errors"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- PANELS (x-show : l'état Livewire de chaque section est conservé quand on change d'onglet) --}}
        @foreach ($sections as $section => $def)
            <div x-show="tab === '{{ $section }}'" role="tabpanel" aria-labelledby="tab-{{ $section }}">

                @if ($def['type'] === 'number')
                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($def['fields'] as $key => [$label, $unit])
                            <div>
                                <label for="f-{{ $section }}-{{ $key }}" class="{{ $labelBase }}">{{ $label }}</label>
                                <div class="relative">
                                    @if ($unit === '$')
                                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">$</span>
                                    @endif

                                    <input type="number" inputmode="decimal" min="0" step="any"
                                        id="f-{{ $section }}-{{ $key }}"
                                        wire:model="form.{{ $section }}.{{ $key }}"
                                        placeholder="0"
                                        class="{{ $inputBase }} {{ $unit === '$' ? 'pl-7' : ($unit ? 'pr-14' : '') }}">

                                    @if ($unit && $unit !== '$')
                                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-gray-400">{{ $unit }}</span>
                                    @endif
                                </div>
                                @error("form.$section.$key")
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($def['fields'] as $key => $label)
                            <label for="f-{{ $section }}-{{ $key }}"
                                class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 transition hover:border-gray-300 hover:bg-gray-50 dark:border-gray-700/70 dark:bg-gray-800/50 dark:text-gray-200 dark:hover:border-gray-600 dark:hover:bg-gray-800">
                                <input type="checkbox" id="f-{{ $section }}-{{ $key }}"
                                    wire:model="form.{{ $section }}.{{ $key }}"
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- PERSONAL --}}
        <div x-show="tab === 'personal'" role="tabpanel" aria-labelledby="tab-personal">
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="f-full-name" class="{{ $labelBase }}">Full name</label>
                    <input type="text" id="f-full-name" wire:model="form.personal.full_name" autocomplete="name" class="{{ $inputBase }}">
                    @error('form.personal.full_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="f-dob" class="{{ $labelBase }}">Date of birth</label>
                    <input type="date" id="f-dob" wire:model="form.personal.dob" autocomplete="bday" class="{{ $inputBase }}">
                    @error('form.personal.dob') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="f-filing" class="{{ $labelBase }}">Filing status</label>
                    <input type="text" id="f-filing" list="filing-statuses" wire:model="form.personal.filing_status" class="{{ $inputBase }}">
                    <datalist id="filing-statuses">
                        <option value="Single"></option>
                        <option value="Married filing jointly"></option>
                        <option value="Married filing separately"></option>
                        <option value="Head of household"></option>
                        <option value="Qualifying surviving spouse"></option>
                    </datalist>
                    @error('form.personal.filing_status') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="f-state" class="{{ $labelBase }}">State of residence</label>
                    <input type="text" id="f-state" wire:model="form.personal.state" class="{{ $inputBase }}">
                    @error('form.personal.state') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="f-dependents" class="{{ $labelBase }}">Number of dependents</label>
                    <input type="number" id="f-dependents" min="0" step="1" inputmode="numeric" wire:model="form.personal.dependents" class="{{ $inputBase }}">
                    @error('form.personal.dependents') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ACTION BAR --}}
        <div class="sticky bottom-0 z-10 -mx-4 mt-8 border-t border-gray-200 bg-white/90 px-4 py-3 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90 sm:mx-0 sm:rounded-xl sm:border">
            <div class="flex items-center justify-between gap-3">
                <p wire:dirty class="text-sm text-amber-600 dark:text-amber-400">Unsaved changes</p>

                <div class="ml-auto flex items-center gap-3">
                    <a href="/dashboard"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/10">
                        Cancel
                    </a>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/50 disabled:cursor-wait disabled:opacity-60">
                        <span wire:loading.remove wire:target="save">Save changes</span>
                        <span wire:loading wire:target="save">Saving…</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
