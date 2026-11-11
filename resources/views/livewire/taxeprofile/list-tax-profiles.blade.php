@php
    $tierStyles = [
        'Foundation' => 'border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-600 dark:bg-gray-700/40 dark:text-gray-300',
        'Growth'     => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300',
        'Elite'      => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300',
    ];
@endphp

<div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6"
     x-data="{ deleting: null, label: '' }"
     @keydown.escape.window="deleting = null">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Tax profiles</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $profiles->count() }} {{ \Illuminate\Support\Str::plural('profile', $profiles->count()) }}
            </p>
        </div>

        <a href="{{ route('tax-profile.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New tax profile
        </a>
    </div>

    {{-- FEEDBACK --}}
    @if (session('message'))
        <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('message') }}
        </div>
    @endif

    @error('delete_error')
        <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    {{-- LIST --}}
    @forelse ($profiles as $profile)
        @php
            $name       = data_get($profile->data, 'personal.full_name') ?: 'Untitled profile';
            $filing     = data_get($profile->data, 'personal.filing_status');
            $state      = data_get($profile->data, 'personal.state');
            $totalIncome = collect(data_get($profile->data, 'income', []))->filter(fn ($v) => is_numeric($v))->sum();
            $tier       = $profile->recommended_tier;
            $meta       = collect([$filing, $state])->filter()->implode(' · ');
        @endphp

        <div wire:key="profile-{{ $profile->id }}"
             class="mb-3 flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-4 transition hover:border-gray-300 dark:border-gray-700/70 dark:bg-gray-800/50 dark:hover:border-gray-600 sm:flex-row sm:items-center sm:justify-between sm:p-5">

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="truncate text-base font-semibold text-gray-900 dark:text-white">{{ $name }}</h2>
                    @if ($tier)
                        <span class="rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $tierStyles[$tier] ?? $tierStyles['Foundation'] }}">{{ $tier }}</span>
                    @endif
                </div>

                @if ($meta)
                    <p class="mt-1 truncate text-sm text-gray-500 dark:text-gray-400">{{ $meta }}</p>
                @endif

                <dl class="mt-3 flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex gap-1.5">
                        <dt>Declared income</dt>
                        <dd class="font-medium text-gray-800 dark:text-gray-200">${{ number_format($totalIncome) }}</dd>
                    </div>
                    <div class="flex gap-1.5">
                        <dt>Created</dt>
                        <dd class="font-medium text-gray-800 dark:text-gray-200">{{ $profile->created_at?->format('M j, Y') }}</dd>
                    </div>
                    <div class="flex gap-1.5">
                        <dt>Updated</dt>
                        <dd class="font-medium text-gray-800 dark:text-gray-200">{{ $profile->updated_at?->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                <a href="{{ route('tax-profile.edit', $profile) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/40 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                    Edit
                </a>

                <button type="button"
                    @click="deleting = {{ $profile->id }}; label = @js($name)"
                    aria-label="Delete {{ $name }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500/40 dark:border-gray-700 dark:text-gray-200 dark:hover:border-red-500/30 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    Delete
                </button>
            </div>
        </div>
    @empty
        {{-- EMPTY STATE --}}
        <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-14 text-center dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">No tax profile yet</h2>
            <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                Create your first profile to get tailored recommendations and a personalized tax report.
            </p>
            <a href="{{ route('tax-profile.create') }}"
                class="mt-5 inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                Create a tax profile
            </a>
        </div>
    @endforelse

    {{-- DELETE CONFIRMATION --}}
    <div x-show="deleting !== null" x-cloak style="display: none"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         role="dialog" aria-modal="true" aria-labelledby="delete-title">

        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="deleting = null"></div>

        <div x-show="deleting !== null" x-transition.opacity.duration.150ms
             class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-700 dark:bg-gray-900">
            <h3 id="delete-title" class="text-lg font-semibold text-gray-900 dark:text-white">Delete this profile?</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium text-gray-900 dark:text-gray-100" x-text="label"></span>
                will be permanently deleted. This cannot be undone.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="deleting = null"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/10">
                    Cancel
                </button>
                <button type="button"
                    @click="$wire.delete(deleting).then(() => deleting = null)"
                    wire:loading.attr="disabled" wire:target="delete"
                    class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500/50 disabled:cursor-wait disabled:opacity-60">
                    <span wire:loading.remove wire:target="delete">Delete profile</span>
                    <span wire:loading wire:target="delete">Deleting…</span>
                </button>
            </div>
        </div>
    </div>
</div>
