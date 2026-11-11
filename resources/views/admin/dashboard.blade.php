<x-app-layout>

 <div class="space-y-6 p-6 max-w-[1600px] mx-auto bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                <span>🛡️</span> ILANDS Solutions — Administration Board
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                
            </p>
        </div>
    </div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 rounded-2xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">CA - This month</span>
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                {{ number_format($revenueMtd, 2, ',', ' ') }} €
            </span>
        </div>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2"></p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 rounded-2xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total revenue</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.854-1.106-2.24 0-3.094l.033-.028c1.172-.879 3.07-.879 4.242 0 .847.635.969 1.56.366 2.22" /></svg>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                {{ number_format($revenueAllTime, 2, ',', ' ') }} €
            </span>
        </div>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2"></p>
    </div>
</div>

@livewire("admin.admin-dashboard")

 </div>
</x-app-layout>