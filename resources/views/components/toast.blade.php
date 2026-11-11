@foreach(['success' => 'bg-emerald-600', 'info' => 'bg-sky-600', 'error' => 'bg-red-600'] as $key => $color)
    @if(session()->has($key))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show" x-cloak
             x-transition.opacity role="status"
             class="fixed bottom-24 left-1/2 z-[60] flex w-max max-w-[90vw] -translate-x-1/2 items-center gap-2 rounded-xl {{ $color }} px-4 py-3 text-sm font-medium text-white shadow-xl md:bottom-6">
            {{ session($key) }}
        </div>
        @break
    @endif
@endforeach