<div>
    @foreach ($notifications as $notification)
        @php
            $isCritical = $notification->data['type'] === '10_percent_remaining';
        @endphp

        <div class="p-4 mb-4 rounded-lg flex items-center justify-between {{ $isCritical ? 'bg-red-100 text-red-800 border border-red-300' : 'bg-yellow-100 text-yellow-800 border border-yellow-300' }}">
            <div>
                <strong class="font-bold">{{ $isCritical ? 'Attention !' : 'Rappel :' }}</strong>
                <span>{{ $notification->data['message'] }}</span>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="/subscription" class="px-3 py-1 text-sm font-semibold text-white rounded bg-indigo-600 hover:bg-indigo-700">
                    Se réabonner
                </a>
                <button wire:click="dismissNotification('{{ $notification->id }}')" class="text-sm underline cursor-pointer">
                    Masquer
                </button>
            </div>
        </div>
    @endforeach
</div>