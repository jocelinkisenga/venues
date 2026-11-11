@php
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Plateforme</h1>
        <p class="text-sm text-slate-500">Vue d'ensemble de toutes les salles. Réservé aux super-administrateurs.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Organisations</p><p class="text-xl font-bold">{{ $stats['organizations'] }}</p></div>
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Salles</p><p class="text-xl font-bold">{{ $stats['venues'] }}</p></div>
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Salles actives</p><p class="text-xl font-bold">{{ $stats['active'] }}</p></div>
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Utilisateurs</p><p class="text-xl font-bold">{{ $stats['users'] }}</p></div>
    </div>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Salle</th>
                    <th class="px-4 py-2">Propriétaire</th>
                    <th class="px-4 py-2 text-right">Réservations</th>
                    <th class="px-4 py-2 text-right">Encaissé 30 j</th>
                    <th class="px-4 py-2">Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    @php $venue = $row['venue']; @endphp
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2">{{ $venue->name }} <span class="text-xs text-slate-500">· {{ $venue->city }}</span></td>
                        <td class="px-4 py-2">{{ $venue->organization?->owner?->email ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->bookings_count }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($row['revenue']) }}</td>
                        <td class="px-4 py-2">
                            <span class="{{ $venue->isSuspended() ? 'text-red-600' : 'text-emerald-600' }}">{{ $venue->isSuspended() ? 'Suspendue' : 'Active' }}</span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click="toggleSuspension({{ $venue->id }})"
                                    wire:confirm="{{ $venue->isSuspended() ? 'Réactiver cette salle ?' : 'Suspendre cette salle ? Ses utilisateurs perdent l\'accès.' }}"
                                    class="text-sm hover:underline {{ $venue->isSuspended() ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $venue->isSuspended() ? 'Réactiver' : 'Suspendre' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>