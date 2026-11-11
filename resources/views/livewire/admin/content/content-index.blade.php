<div class="space-y-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Content Management</h1>
        <a href="/admin/content/create" class="inline-flex justify-center items-center px-5 py-3 rounded-xl bg-black dark:bg-white text-white dark:text-black hover:bg-gray-800 dark:hover:bg-gray-100 text-sm font-semibold transition shadow-sm">
            + Add content
        </a>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 p-4 border border-gray-100 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 shadow-sm transition-colors duration-200">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par titre..." class="flex-1 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary placeholder-gray-400 dark:placeholder-gray-500">
        
        <select wire:model.live="filterType" class="w-full sm:w-48 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary">
            <option value="">Tous types</option>
            <option value="video">Vidéo</option>
            <option value="blog">Blog</option>
        </select>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm transition-colors duration-200">
        
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 bg-gray-50/70 dark:bg-gray-800/40">
                        <th class="px-6 py-4">Contenu</th>
                        <th class="px-6 py-4">Type / Accès</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($contents as $content)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap flex items-center gap-4">
                            @if($content->thumbnail)
                                <img src="{{ asset('storage/' . $content->thumbnail) }}" class="w-12 h-12 rounded-xl object-cover bg-gray-100 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                            @endif
                            <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $content->title }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-mono font-medium text-gray-500 dark:text-gray-400">{{ strtoupper($content->type) }}</span>
                                <span class="text-[11px] text-blue-600 dark:text-blue-400 font-bold tracking-wide">{{ strtoupper($content->access_level) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full 
                                {{ $content->status === 'published' 
                                    ? 'bg-gray-900 text-white dark:bg-white dark:text-black' 
                                    : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                {{ $content->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-3">
                                <button wire:click="togglePublish({{ $content->id }})"
                                        class="text-xs font-bold px-3 py-1.5 rounded-xl border transition
                                        {{ $content->status === 'published' 
                                            ? 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700' 
                                            : 'bg-black border-black text-white hover:bg-gray-800 dark:bg-white dark:border-white dark:text-black dark:hover:bg-gray-100' }}">
                                    {{ $content->status === 'published' ? 'Désactiver' : 'Publier' }}
                                </button>
                                <a href="admin/content/edit/{{ $content->id }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white transition">Éditer</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 md:hidden">
            @foreach($contents as $content)
            <div class="p-5 flex flex-col gap-4 bg-white dark:bg-gray-900">
                
                <div class="flex items-center gap-3.5">
                    @if($content->thumbnail)
                        <img src="{{ asset('storage/' . $content->thumbnail) }}" class="w-14 h-14 rounded-xl object-cover bg-gray-100 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shrink-0">
                    @endif
                    <div class="truncate">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate" title="{{ $content->title }}">{{ $content->title }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] font-mono text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 px-1.5 py-0.5 rounded">{{ strtoupper($content->type) }}</span>
                            <span class="text-[10px] text-blue-600 dark:text-blue-400 font-extrabold tracking-wide">{{ strtoupper($content->access_level) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-b border-gray-50 dark:border-gray-800/40 py-2.5 text-xs">
                    <span class="text-gray-400 dark:text-gray-500">Statut de diffusion</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-[9px] uppercase font-bold tracking-wider rounded-full 
                        {{ $content->status === 'published' 
                            ? 'bg-gray-900 text-white dark:bg-white dark:text-black' 
                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                        {{ $content->status }}
                    </span>
                </div>

                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="admin/content/edit/{{ $content->id }}" class="flex-1 text-center py-2 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-xl transition">
                        Éditer
                    </a>
                    <button wire:click="togglePublish({{ $content->id }})"
                            class="flex-1 text-center py-2 text-xs font-bold rounded-xl transition shadow-sm
                            {{ $content->status === 'published' 
                                ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 hover:bg-red-100' 
                                : 'bg-black text-white dark:bg-white dark:text-black hover:bg-gray-800' }}">
                        {{ $content->status === 'published' ? 'Désactiver' : 'Publier' }}
                    </button>
                </div>

            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900">
            {{ $contents->links() }}
        </div>
    </div>
</div>
