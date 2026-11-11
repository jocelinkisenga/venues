<div class="space-y-6 bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        
        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs transition-all duration-200 hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tokens</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 text-lg"></span>
            </div>
            <div class="mt-4 flex items-baseline space-x-2">
                <span class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($tokensRemaining + $tokensUsed) }}</span>
                <span class="text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-950/40 dark:text-green-400 px-1.5 py-0.5 rounded">+12.5%</span>
            </div>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500"></p>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs transition-all duration-200 hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Membres sur la plateforme</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 text-lg">👥</span>
            </div>
            <div class="mt-4 flex items-baseline space-x-2">
                <span class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $totalUsers }}</span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 dark:bg-blue-950/40 dark:text-blue-400 px-1.5 py-0.5 rounded">Actifs</span>
            </div>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500"></p>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs transition-all duration-200 hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Discussions IA lancées</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 text-lg">💬</span>
            </div>
            <div class="mt-4 flex items-baseline space-x-2">
                <span class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $activeChats }}</span>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 dark:bg-purple-950/40 dark:text-purple-400 px-1.5 py-0.5 rounded">Sessions</span>
            </div>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500"></p>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs transition-all duration-200 hover:scale-[1.02]">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Rapports  générés</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 text-lg">📄</span>
            </div>
            <div class="mt-4 flex items-baseline space-x-2">
                <span class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $generatedReports }}</span>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-950/40 dark:text-amber-400 px-1.5 py-0.5 rounded"></span>
            </div>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500"></p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0 mb-4">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    ⚡ Infrastructure Monitor
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
            <div class="flex space-x-6 text-xs font-mono">
                <div><span class="text-gray-400">Used tokens:</span> <strong class="text-gray-900 dark:text-white">{{ number_format($tokensUsed) }}</strong></div>
                <div><span class="text-gray-400">Remaining:</span> <strong class="text-indigo-600 dark:text-indigo-400">{{ number_format($tokensRemaining) }}</strong></div>
            </div>
        </div>

        <div class="relative w-full h-4 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner">
            <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-500 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"
                 style="width: {{ $tokensUsagePercentage }}%"></div>
        </div>
        <div class="flex justify-between items-center mt-2 text-[11px] text-gray-400 dark:text-gray-500">
            <span>alert : 80%</span>
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ number_format($tokensUsagePercentage, 1) }}% used</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs flex flex-col justify-between">
            <div class="mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Performances </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
            
            <div class="h-64 relative w-full" x-data="{
                init() {
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor = isDark ? '#1F2937' : '#F3F4F6';
                    const textColor = isDark ? '#9CA3AF' : '#6B7280';

                    const ctx = document.getElementById('analyticsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {{ json_encode($chartData['labels']) }},
                            datasets: [
                                {
                                    label: 'Revenus ($)',
                                    data: {{ json_encode($chartData['revenue']) }},
                                    borderColor: '#10B981',
                                    backgroundColor: 'transparent',
                                    borderWidth: 3,
                                    tension: 0.3
                                },
                                {
                                    label: 'Tokens IA',
                                    data: {{ json_encode($chartData['tokens']) }},
                                    borderColor: '#6366F1',
                                    backgroundColor: 'transparent',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    tension: 0.3,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { 
                                    ticks: { color: textColor },
                                    grid: { display: false } 
                                },
                                y: { 
                                    ticks: { color: textColor },
                                    grid: { color: gridColor } 
                                },
                                y1: { 
                                    position: 'right', 
                                    ticks: { color: textColor },
                                    grid: { display: false } 
                                }
                            }
                        }
                    });
                }
            }">
                <canvas id="analyticsChart"></canvas>
            </div>
        </div>

 {{--        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Dernières Soumissions (MVP)</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4"></p>
                
                <div class="flow-root">
                    <ul class="-my-4 divide-y divide-gray-100 dark:divide-gray-800/60">
                        @forelse($recentActivities as $activity)
                            <li class="py-3.5 flex items-start justify-between gap-x-4">
                                <div class="flex items-start gap-x-3 truncate">
                                    <span class="text-xl p-1 bg-gray-50 dark:bg-gray-800 rounded-lg">📄</span>
                                    <div class="truncate">
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate" title="{{ $activity->file_name }}">
                                            {{ Str::limit($activity->file_name, 22) }}
                                        </p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate italic">
                                            "{{ Str::limit($activity->message, 30) ?: 'Aucun prompt attaché' }}"
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end whitespace-nowrap text-[10px] text-gray-400">
                                    <span class="font-medium text-gray-600 dark:text-gray-300">Traité</span>
                                    <span>{{ $activity->created_at->format('d/m H:i') }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-xs text-gray-400 italic">
                                En attente de téléversements utilisateurs.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            
            <a href="#" class="mt-4 block text-center rounded-lg bg-gray-50 dark:bg-gray-800 px-3 py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                Voir toute la bibliothèque →
            </a>
        </div> --}}
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
