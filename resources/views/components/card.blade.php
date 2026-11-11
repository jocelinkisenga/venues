@props(['padding' => 'p-5', 'flush' => false])

<section {{ $attributes->class([
    'rounded-2xl border border-slate-200/70 bg-white shadow-sm',
    'dark:border-slate-800 dark:bg-slate-900',
    $padding => ! $flush,
    'overflow-hidden' => $flush,
]) }}>
    @if(isset($header))
        <header class="flex items-center justify-between gap-3 border-b border-slate-200/70 px-5 py-4 dark:border-slate-800">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $header }}</h2>
            @isset($headerActions)
                <div class="flex items-center gap-2">{{ $headerActions }}</div>
            @endisset
        </header>
    @endif

    <div @class([$padding => isset($header) && ! $flush])>
        {{ $slot }}
    </div>
</section>