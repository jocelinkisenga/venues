@props(['name', 'label', 'type' => 'text'])

@php
    $id = $attributes->get('id') ?? $name;
    $error = $errors->first($name);
    $tone = $error
        ? 'border-red-400 focus:border-red-500 focus:ring-red-500/15 dark:border-red-500/60'
        : 'border-stone-200 focus:border-stone-900 focus:ring-stone-900/10 dark:border-stone-700 dark:focus:border-amber-400 dark:focus:ring-amber-400/20';
@endphp

<div class="space-y-1.5">
    <label for="{{ $id }}" class="block text-sm font-medium text-stone-700 dark:text-stone-300">{{ $label }}</label>

    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ old($name) }}"
           @if($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
           {{ $attributes->except('id')->class([
                'block h-12 w-full rounded-xl border bg-white px-4 text-base text-stone-900 placeholder:text-stone-400 transition focus:outline-none focus:ring-4 dark:bg-stone-800 dark:text-stone-100',
                $tone,
           ]) }}>

    @if($error)
        <p id="{{ $id }}-error" class="text-xs font-medium text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>