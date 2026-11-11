@props(['icon', 'title', 'meta', 'href', 'action', 'tone' => 'amber'])

@php
    $tones = [
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-400',
        'sky' => 'bg-sky-50 text-sky-600 dark:bg-sky-400/10 dark:text-sky-400',
        'violet' => 'bg-violet-50 text-violet-600 dark:bg-violet-400/10 dark:text-violet-400',
        'red' => 'bg-red-50 text-red-600 dark:bg-red-400/10 dark:text-red-400',
    ];
@endphp

<li class="flex items-center gap-4 px-5 py-3.5">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $tones[$tone] }}">
        <x-ui-icon :name="$icon" class="h-5 w-5" />
    </span>

    <div class="min-w-0 flex-1">
        <p class="truncate text-sm font-medium text-slate-900 dark:text-slate-100">{{ $title }}</p>
        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $meta }}</p>
    </div>

    <a href="{{ $href }}" wire:navigate
       class="shrink-0 rounded-lg px-3 py-1.5 text-sm font-medium text-amber-700 transition hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-400/10">
        {{ $action }}
    </a>
</li>