@props(['status'])

@php
    $tone = match ($status->value) {
        'option' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-300',
        'confirmed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-300',
        'in_progress' => 'bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-400/10 dark:text-sky-300',
        'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-400/10 dark:text-red-300',
        default => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-slate-800 dark:text-slate-300',
    };
@endphp

<span {{ $attributes->class(['inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset', $tone]) }}>
    {{ $status->label() }}
</span>