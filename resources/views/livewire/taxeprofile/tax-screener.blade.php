@php
    use App\Support\ScreenerProfile;

    // étape => [champ, question, aide, options]
    $questions = [
        1 => ['income_source',   'What’s your main source of income?', 'This helps us pick the right topics for you.', ScreenerProfile::INCOME_SOURCES],
        2 => ['annual_income',   'About how much do you earn per year?', 'A rough range is fine.',                      ScreenerProfile::INCOME_BRACKETS],
        3 => ['tracking_status', 'Do you track your deductible expenses?', 'Mileage, supplies, phone, home office…',      ScreenerProfile::TRACKING],
    ];
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-[#071022] text-slate-900 dark:text-white flex flex-col items-center px-5 py-10 sm:py-16 transition-colors duration-300">
    <div class="w-full max-w-xl">

        {{-- En-tête --}}
        <div class="mb-8 flex items-center justify-between text-sm">
            <span class="font-bold tracking-tight">Unstack My Taxes</span>
            @unless($completed)
                <span class="text-slate-500 dark:text-slate-400">Free tax screener · 2 min</span>
            @endunless
        </div>

        {{-- Progression --}}
        @unless($completed)
            <div class="mb-3 flex gap-2" role="progressbar" aria-valuemin="1" aria-valuemax="{{ $totalSteps }}" aria-valuenow="{{ $step }}">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <div class="h-1.5 flex-1 rounded-full transition-colors duration-500 {{ $i <= $step ? 'bg-blue-600' : 'bg-slate-200 dark:bg-white/10' }}"></div>
                @endfor
            </div>
            <p class="mb-6 text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">Step {{ $step }} of {{ $totalSteps }}</p>
        @endunless

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-blue-900/5 dark:border-white/10 dark:bg-white/5 sm:p-10">

            <div wire:key="panel-{{ $completed ? 'done' : $step }}"
                 x-data="{ show: false }" x-init="$nextTick(() => show = true)"
                 x-show="show" x-transition.opacity.duration.300ms>

            @if($completed)
                {{-- RÉSULTAT --}}
                <div class="text-center">
                    <div class="mx-auto mb-6 grid h-16 w-16 place-items-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight">Thanks, {{ $first_name }}!</h2>
                    <p class="mt-3 text-slate-600 dark:text-slate-300">{{ $recommendation['message'] }}</p>

                    <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-6 dark:border-blue-500/20 dark:bg-blue-500/10">
                        <p class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Recommended plan</p>
                        <p class="mt-2 text-3xl font-bold">{{ $recommendation['name'] }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $recommendation['price'] }}</p>
                    </div>

                    <a href="{{ url('/register?plan=' . $recommendation['tier']) }}"
                       class="mt-8 inline-flex h-12 w-full items-center justify-center rounded-xl bg-blue-600 px-6 text-base font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/40">
                        Start my profile
                    </a>
                    <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Your answers also help our assistant give more relevant guidance.</p>
                </div>

            @elseif($step <= 3)
                {{-- ÉTAPES 1 à 3 : cartes de choix --}}
                @php [$field, $title, $hint, $options] = $questions[$step]; @endphp

                <form wire:submit="nextStep">
                    <h2 class="text-2xl font-bold tracking-tight">{{ $title }}</h2>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">{{ $hint }}</p>

                    <div class="mt-6 space-y-3" role="radiogroup" aria-label="{{ $title }}">
                        @foreach($options as $value => [$label, $help])
                            <label wire:key="{{ $field }}-{{ $value }}" class="block cursor-pointer">
                                <input type="radio" wire:model.live="{{ $field }}" value="{{ $value }}" class="peer sr-only">
                                <div class="flex items-center gap-4 rounded-2xl border-2 border-slate-200 bg-white p-4 transition hover:border-blue-300
                                            peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-focus-visible:ring-4 peer-focus-visible:ring-blue-500/30
                                            dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-500/50 dark:peer-checked:border-blue-500 dark:peer-checked:bg-blue-500/10
                                            peer-checked:[&_.ring]:border-blue-600 peer-checked:[&_.dot]:scale-100">
                                    <span class="ring grid h-5 w-5 shrink-0 place-items-center rounded-full border-2 border-slate-300 transition dark:border-white/30">
                                        <span class="dot h-2.5 w-2.5 scale-0 rounded-full bg-blue-600 transition-transform"></span>
                                    </span>
                                    <span>
                                        <span class="block font-semibold">{{ $label }}</span>
                                        @if($help)<span class="block text-sm text-slate-500 dark:text-slate-400">{{ $help }}</span>@endif
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error($field)<p class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror

                    <div class="mt-8 flex gap-3">
                        @if($step > 1)
                            <button type="button" wire:click="previousStep"
                                    class="h-12 rounded-xl border border-slate-300 px-5 font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-400/30 dark:border-white/15 dark:text-slate-200 dark:hover:bg-white/10">
                                Back
                            </button>
                        @endif
                        <button type="submit" @disabled(blank($this->{$field}))
                                class="group inline-flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/40 disabled:cursor-not-allowed disabled:opacity-40 disabled:shadow-none">
                            Next
                            <svg class="h-4 w-4 transition group-enabled:group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </form>

            @else
                {{-- ÉTAPE 4 : coordonnées --}}
                <form wire:submit="submit">
                    <h2 class="text-2xl font-bold tracking-tight">Last step</h2>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Where should we send your recommendation?</p>

                    <div class="mt-6 space-y-4">
                        <div>
                            <label for="first_name" class="mb-1.5 block text-sm font-medium">First name</label>
                            <input id="first_name" type="text" wire:model="first_name" autocomplete="given-name"
                                   class="w-full rounded-xl border border-slate-300 bg-white p-3.5 text-base outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-500/20 dark:border-white/15 dark:bg-white/5">
                            @error('first_name')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                            <input id="email" type="email" wire:model="email" autocomplete="email" inputmode="email"
                                   class="w-full rounded-xl border border-slate-300 bg-white p-3.5 text-base outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-500/20 dark:border-white/15 dark:bg-white/5">
                            @error('email')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tax_concern" class="mb-1.5 block text-sm font-medium">Your main tax concern <span class="font-normal text-slate-400">(optional)</span></label>
                            <textarea id="tax_concern" wire:model="tax_concern" rows="3" maxlength="500"
                                      class="w-full rounded-xl border border-slate-300 bg-white p-3.5 text-base outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-500/20 dark:border-white/15 dark:bg-white/5"></textarea>
                            @error('tax_concern')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" wire:click="previousStep"
                                class="h-12 rounded-xl border border-slate-300 px-5 font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-400/30 dark:border-white/15 dark:text-slate-200 dark:hover:bg-white/10">
                            Back
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                                class="inline-flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/40 disabled:opacity-60">
                            <span wire:loading.remove wire:target="submit">View my recommendation</span>
                            <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" opacity=".25"/><path d="M21 12a9 9 0 00-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                                Analyzing…
                            </span>
                        </button>
                    </div>

                    <p class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
                        Educational guidance only. By continuing you agree to our <a href="{{ url('/privacy') }}" class="underline hover:text-blue-600">Privacy Policy</a>.
                    </p>
                </form>
            @endif
            </div>
        </div>
    </div>
</div>
