@props(['href', 'icon' => null, 'active' => false])
<a href="{{ $href }}" wire:navigate
   {{ $attributes->class([
        'group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-colors',
        'bg-amber-500/10 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300' => $active,
        'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100' => ! $active,
   ]) }}
   @if($active) aria-current="page" @endif>
    @if($icon)
        <x-ui-icon :name="$icon" class="h-5 w-5 shrink-0 {{ $active ? 'text-amber-500' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500' }}" />
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>