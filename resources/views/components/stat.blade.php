@props(['label', 'value', 'icon', 'delta' => null])

<x-card class="transition hover:shadow-md">
    <div class="flex items-start justify-between gap-3">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-400">
            <x-ui-icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>

    <p class="mt-3 truncate text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $value }}</p>

    @if(! is_null($delta))
        <p class="mt-1.5 flex items-center gap-1 text-xs font-medium {{ $delta >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
            <x-ui-icon name="trend" class="h-3.5 w-3.5 {{ $delta < 0 ? 'rotate-180' : '' }}" />
            {{ $delta >= 0 ? '+' : '' }}{{ $delta }} % par rapport au mois précédent
        </p>
    @endif
</x-card>