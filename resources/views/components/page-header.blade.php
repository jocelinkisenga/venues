@props([
    'title',
    'subtitle' => null,
    'back' => null,
    'backLabel' => 'Retour',
])

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        @if($back)
            <a href="{{ $back }}" wire:navigate
               class="mb-2 inline-flex items-center gap-1 text-sm text-slate-500 transition hover:text-slate-800 dark:hover:text-slate-200">
                <x-icon name="chevron" class="h-4 w-4 rotate-90" />
                {{ $backLabel }}
            </a>
        @endif

        <h1 class="truncate text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h1>

        @if($subtitle)
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>