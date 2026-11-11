<div class="space-y-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="w-full sm:w-auto">
            <select wire:model.live="filterStatus" class="w-full sm:w-64 rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 shadow-sm focus:border-primary focus:ring-1 focus:ring-primary transition duration-150">
                <option value="">All subscriptions</option>
                <option value="active">Active</option>
                <option value="canceled">Cancelled</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm transition-colors duration-200">
       
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 bg-gray-50/70 dark:bg-gray-800/40">
                        <th class="px-6 py-4">Model name</th>
                        <th class="px-6 py-4">Api key</th>
                        <th class="px-6 py-4">Url</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($models as $model)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-sm text-gray-900 dark:text-gray-100"></div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 font-mono mt-0.5"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-medium uppercase text-gray-600 dark:text-gray-400 tracking-wide"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] uppercase font-bold tracking-wider  bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400
                                    ">
                                
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-4">
                                <button class="text-xs font-bold text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary hover:underline transition">Details</button>
                                <button wire:confirm="Suspendre cet utilisateur ?" class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:underline transition">Suspend</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 md:hidden">
            @foreach($models as $model)
            <div class="p-5 flex flex-col gap-4 bg-white dark:bg-gray-900">
                
                <div class="flex items-start justify-between gap-2">
                    <div class="truncate">
                        <h4 class="font-bold text-base text-gray-900 dark:text-white truncate">{{ $model->name }}</h4>
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-mono truncate mt-0.5">{{ $model->email }}</p>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">
                        {{ $model->role }}
                    </span>
                </div>

                <div class="flex items-center justify-between border-t border-b border-gray-50 dark:border-gray-800/60 py-2.5">
                    <span class="text-xs text-gray-400 dark:text-gray-500">Abonnement</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider 
                        {{ $model->stripe_status === 'active' 
                            ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' 
                            : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                        {{ $k->stripe_status ?? 'no subscription' }}
                    </span>
                </div>

                <div class="flex items-center justify-end gap-3 pt-1">
                    <button class="flex-1 text-center py-2 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-xs font-bold text-gray-900 dark:text-gray-100 rounded-xl transition">
                        Détails
                    </button>
                    <button wire:confirm="Suspendre cet utilisateur ?" class="flex-1 text-center py-2 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold text-red-600 dark:text-red-400 rounded-xl transition">
                        Suspend
                    </button>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</div>
