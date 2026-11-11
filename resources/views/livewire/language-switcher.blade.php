<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" 
            class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100 transition">
        
        @if(App::getLocale() === 'fr')
            <svg viewBox="0 0 3 2" class="w-5 h-4 rounded-sm"><rect width="1" height="2" fill="#002395"/><rect width="1" height="2" x="1" fill="#fff"/><rect width="1" height="2" x="2" fill="#ed2939"/></svg>
            <span>FR</span>
        @else
            <svg viewBox="0 0 60 30" class="w-5 h-4 rounded-sm"><path d="M0,0 v30 h60 v-30 z" fill="#012169"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#C8102E" stroke-width="4"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/></svg>
            <span>EN</span>
        @endif

        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg z-50 py-1">
        
        <button wire:click="switchLanguage('fr')" @click="open = false"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 space-x-3 {{ App::getLocale() === 'fr' ? 'bg-blue-50 text-blue-600' : '' }}">
            <svg viewBox="0 0 3 2" class="w-5 h-4 rounded-sm"><rect width="1" height="2" fill="#002395"/><rect width="1" height="2" x="1" fill="#fff"/><rect width="1" height="2" x="2" fill="#ed2939"/></svg>
            <span>Français</span>
        </button>

        <button wire:click="switchLanguage('en')" @click="open = false"
                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 space-x-3 {{ App::getLocale() === 'en' ? 'bg-blue-50 text-blue-600' : '' }}">
            <svg viewBox="0 0 60 30" class="w-5 h-4 rounded-sm"><path d="M0,0 v30 h60 v-30 z" fill="#012169"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#C8102E" stroke-width="4"/><path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/><path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/></svg>
            <span>English</span>
        </button>
    </div>
</div>