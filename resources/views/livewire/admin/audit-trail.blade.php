<div class="space-y-4">
    <div>
        <h1 class="text-2xl font-bold">Journal d'audit</h1>
        <p class="text-sm text-slate-500">200 dernières modifications : devis, remises, paiements, réservations.</p>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-white/40 bg-white/70 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-3 py-2">Date</th>
                    <th class="px-3 py-2">Utilisateur</th>
                    <th class="px-3 py-2">Action</th>
                    <th class="px-3 py-2">Objet</th>
                    <th class="px-3 py-2">Détail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-t border-slate-200/70 align-top dark:border-slate-800">
                        <td class="whitespace-nowrap px-3 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">{{ $log->user?->name ?? 'Système' }}</td>
                        <td class="px-3 py-2">{{ $log->action }}</td>
                        <td class="px-3 py-2">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                        <td class="px-3 py-2 font-mono text-xs break-all">{{ json_encode($log->changes, JSON_UNESCAPED_UNICODE) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-4 text-slate-500">Aucune entrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
