@props(['variant' => 'primary', 'type' => 'button', 'href' => null])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition
             focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-2
             disabled:cursor-not-allowed disabled:opacity-50 dark:focus-visible:ring-offset-slate-900';

    $variants = [
        'primary' => 'bg-amber-500 text-white shadow-sm hover:bg-amber-600',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800',
        'danger' => 'bg-red-600 text-white shadow-sm hover:bg-red-700',
        'ghost' => 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
    ];
@endphp

@if($href)
    <a href="{{ $href }}" wire:navigate {{ $attributes->class([$base, $variants[$variant]]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$base, $variants[$variant]]) }}>{{ $slot }}</button>
@endif