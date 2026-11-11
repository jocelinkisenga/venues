<x-app-layout>
    {{-- <x-common.page-breadcrumb pageTitle="List of documents" />
    <div class="space-y-6">
        <x-common.component-card title="Analysed documents">
            <x-tables.basic-tables.basic-tables-two />
        </x-common.component-card>
    </div> --}}

    <div class="w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors duration-200">

            <!-- En-tête de la section -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>📂</span> Mes Documents Uploadés
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Historique centralisé de tous les fichiers partagés avec l'IA pour traitement et analyses fiscales.
                    </p>
                </div>
                <span class="bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-full whitespace-nowrap border border-blue-100 dark:border-blue-800/50">
                    {{ $documents->count() }} document(s) au total
                </span>
            </div>

            @if($documents->isEmpty())
                <!-- État Vide (Empty State) Global -->
                <div class="p-12 text-center text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-900">
                    <div class="text-5xl mb-4 opacity-50 grayscale">📁</div>
                    <p class="text-sm font-bold text-gray-600 dark:text-gray-300">
                        Votre bibliothèque est vide
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 max-w-sm mx-auto">
                        Les documents que vous soumettez dans l'onglet Chat s'ajouteront ici automatiquement.
                    </p>
                </div>
            @else
                
                <!-- 1. VUE TABLEAU (Ordinateur et Tablettes — md et +) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 dark:bg-gray-800/40 border-b border-gray-100 dark:border-gray-800 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Nom du document</th>
                                <th class="p-4">Taille</th>
                                <th class="p-4">Format</th>
                                <th class="p-4">Date d'importation</th>
                                <th class="p-4 w-1/3">Prompt de contexte</th>
                                <th class="p-4 pr-6 text-center">Traitement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                            @foreach($documents as $doc)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition duration-150">
                                <td class="p-4 pl-6 font-semibold text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-xl bg-gray-50 dark:bg-gray-800 p-1.5 rounded-lg border border-gray-100 dark:border-gray-700">📄</span>
                                        <span title="{{ $doc['name'] }}" class="truncate max-w-[200px]">
                                            {{ Str::limit($doc['name'], 30) }}
                                        </span>
                                    </div>
                                </td>

                                <td class="p-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $doc['size'] }}
                                </td>

                                <td class="p-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded text-[10px] font-bold font-mono uppercase border border-gray-200 dark:border-gray-700">
                                        {{ last(explode('/', $doc['type'])) }}
                                    </span>
                                </td>

                                <td class="p-4 whitespace-nowrap text-xs text-gray-400 dark:text-gray-500">
                                    {{ $doc['date'] }}
                                </td>

                                <td class="p-4 text-gray-500 dark:text-gray-400 italic text-xs truncate max-w-[250px]" title="{{ $doc['context_prompt'] }}">
                                    "{{ $doc['context_prompt'] ?: 'Document envoyé sans texte.' }}"
                                </td>

                                <td class="p-4 pr-6 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20">
                                        <span class="w-1.5 h-1.5 bg-blue-500 dark:bg-blue-400 rounded-full mr-1.5"></span>
                                        Indexé & Traité
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 2. VUE CARTES (Mobile uniquement — masqué sur PC) -->
                <div class="grid grid-cols-1 divide-y divide-gray-100 dark:divide-gray-800 md:hidden bg-white dark:bg-gray-900">
                    @foreach($documents as $doc)
                    <div class="p-5 flex flex-col gap-3 hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition duration-150">
                        
                        <!-- En-tête de carte : Nom et Format -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start space-x-3 truncate">
                                <span class="text-xl bg-gray-50 dark:bg-gray-800 p-1.5 rounded-lg border border-gray-100 dark:border-gray-700 shrink-0">📄</span>
                                <div class="truncate mt-0.5">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100 truncate" title="{{ $doc['name'] }}">
                                        {{ $doc['name'] }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="font-mono">{{ $doc['size'] }}</span>
                                        <span>•</span>
                                        <span>{{ $doc['date'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="shrink-0 px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded text-[10px] font-bold font-mono uppercase border border-gray-200 dark:border-gray-700">
                                {{ last(explode('/', $doc['type'])) }}
                            </span>
                        </div>

                        <!-- Prompt de Contexte -->
                        <div class="bg-gray-50/80 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800 mt-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400 italic line-clamp-2" title="{{ $doc['context_prompt'] }}">
                                "{{ $doc['context_prompt'] ?: 'Document envoyé sans texte additionnel.' }}"
                            </p>
                        </div>

                        <!-- Statut de traitement -->
                        <div class="flex justify-end mt-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20">
                                <span class="w-1.5 h-1.5 bg-blue-500 dark:bg-blue-400 rounded-full mr-1.5 animate-pulse"></span>
                                Indexé & Traité
                            </span>
                        </div>

                    </div>
                    @endforeach
                </div>
                
            @endif

        </div>
    </div>

</x-app-layout>
