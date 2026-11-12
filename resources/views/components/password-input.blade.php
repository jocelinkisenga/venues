@props(['name', 'label'])

@php
    $id = $attributes->get('id') ?? $name;
    $error = $errors->first($name);
    $tone = $error
        ? 'border-red-400 focus:border-red-500 focus:ring-red-500/15 dark:border-red-500/60'
        : 'border-stone-200 focus:border-stone-900 focus:ring-stone-900/10 dark:border-stone-700 dark:focus:border-amber-400 dark:focus:ring-amber-400/20';
@endphp

<div class="space-y-1.5" x-data="{ visible: false }">
    <label for="{{ $id }}" class="block text-sm font-medium text-stone-700 dark:text-stone-300">{{ $label }}</label>

    <div class="relative">
        <input id="{{ $id }}" name="{{ $name }}" :type="visible ? 'text' : 'password'"
               @if($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
               {{ $attributes->except('id')->class([
                    'block h-12 w-full rounded-xl border bg-white pl-4 pr-12 text-base text-stone-900 placeholder:text-stone-400 transition focus:outline-none focus:ring-4 dark:bg-stone-800 dark:text-stone-100',
                    $tone,
               ]) }}>

        <button type="button" @click="visible = !visible"
                :aria-label="visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-xl text-stone-400 transition hover:text-stone-700 focus:outline-none focus-visible:text-stone-900 dark:hover:text-stone-200">
            <x-ui-icon name="eye" class="h-5 w-5" x-show="!visible" />
            <x-ui-icon name="eye-off" class="h-5 w-5" x-show="visible" x-cloak />
        </button>
    </div>

    @if($error)
        <p id="{{ $id }}-error" class="text-xs font-medium text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>