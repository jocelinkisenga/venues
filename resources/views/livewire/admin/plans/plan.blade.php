<div class="space-y-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Plan Management</h1>
        <a href="{{ route('admin.create.plan') }}" class="inline-flex justify-center items-center px-5 py-3 rounded-xl bg-black dark:bg-white text-white dark:text-black hover:bg-gray-800 dark:hover:bg-gray-100 text-sm font-semibold transition shadow-sm">
            + Add plan
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm transition-colors duration-200">
       
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-gray-100 border-y dark:border-white/[0.05]">
                    <th class="px-6 py-3">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">name</p>
                        </div>
                    </th>
                    <th class="px-6 py-3">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Price</p>
                        </div>
                    </th>
                    <th class="px-6 py-3">
                        <div class="flex items-center col-span-2">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tokens</p>
                        </div>
                    </th>
                    <th class="px-6 py-3">
                        <div class="flex items-center col-span-2">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">CR</p>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/[0.05]">
                @foreach ($plans as $plan)
                    <tr>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                    {{ $plan->name }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $plan->price }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $plan->analysis_quota }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center">
                                <a href="plan/edit/{{ $plan->id }}" class="px-3 py-1.5 text-xs font-bold bg-green-400 dark:bg-green-400 text-white-500 dark:text-white-400 hover:text-white dark:hover:text-white-400 transition">Éditer</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 md:hidden">
            @foreach($plans as $plan)
            <div class="p-5 flex flex-col gap-4 bg-white dark:bg-gray-900">
                
                <div class="flex items-start justify-between gap-2">
                    <div class="truncate">
                        <h4 class="font-bold text-base text-gray-900 dark:text-white truncate">{{$plan->name}}</h4>
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-mono truncate mt-0.5">{{$plan->analysis_quota}} Tokens</p>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">
                       $ {{$plan->price}}
                    </span>
                </div>



                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="plan/edit/{{ $plan->id }}" class="flex-1 text-center py-2 bg-green-100 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-xs font-bold text-gray-900 dark:text-gray-100 rounded-xl transition">
                        edit
                    </a>
                    <button wire:confirm="Suspendre cet utilisateur ?" class="flex-1 text-center py-2 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold text-red-600 dark:text-red-400 rounded-xl transition">
                        Suspendre
                    </button>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</div>
