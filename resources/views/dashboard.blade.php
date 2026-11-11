<x-app-layout>
@php
    $k = $data['kpis'];
    $a = $data['actions'];
    $firstName = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
    $pending = $a['expiringOptions']->count() + $a['balanceDue']->count()
        + $a['unsignedContracts']->count() + $a['guestsPending']->count();
@endphp

<x-page-header
    :title="'Bonjour '.$firstName"
    :subtitle="$venue->name.' · '.now()->locale('fr')->translatedFormat('l j F Y')">
    @if($canSell)
        <x-slot:actions>
            <x-button href="{{ route('bookings.create') }}">
                <x-ui-icon name="plus" class="h-4 w-4" />
                Nouvelle réservation
            </x-button>
        </x-slot:actions>
    @endif
</x-page-header>

{{-- Indicateurs --}}
<div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
    <x-stat label="Encaissé ce mois" :value="$venue->money($k['revenue'])" icon="wallet" :delta="$k['revenueDelta']" />
    <x-stat label="Réservations ce mois" :value="$k['bookings']" icon="calendar" />
    <x-stat label="Occupation, 30 jours" :value="$k['occupancy'].' %'" icon="chart" />
    <x-stat label="Options en attente" :value="$k['pendingOptions']" icon="clock" />
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-3">

    {{-- Colonne principale --}}
    <div class="space-y-6 xl:col-span-2">

        {{-- À traiter --}}
        <x-card flush>
            <x-slot:header>
                <span class="flex items-center gap-2">
                    À traiter
                    @if($pending > 0)
                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">{{ $pending }}</span>
                    @endif
                </span>
            </x-slot:header>

            @if($pending === 0)
                <div class="flex flex-col items-center gap-2 px-5 py-10 text-center">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <x-ui-icon name="check" class="h-6 w-6" />
                    </span>
                    <p class="text-sm font-medium">Rien à traiter pour le moment</p>
                    <p class="text-xs text-slate-500">Les options à confirmer, soldes et contrats en attente apparaîtront ici.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($a['expiringOptions'] as $booking)
                        <x-action-row
                            tone="red"
                            icon="clock"
                            :title="$booking->client_name.' · '.$booking->reference"
                            :meta="'Option expire '.$booking->option_expires_at->diffForHumans().' · '.$booking->space->name"
                            :href="route('bookings.quote', $booking)"
                            action="Confirmer" />
                    @endforeach

                    @foreach($a['balanceDue'] as $row)
                        <x-action-row
                            tone="amber"
                            icon="wallet"
                            :title="$row['booking']->client_name.' · '.$row['booking']->reference"
                            :meta="'Reste à encaisser : '.$venue->money($row['due']).' · événement le '.$row['booking']->starts_at->format('d/m')"
                            :href="route('bookings.quote', $row['booking'])"
                            action="Encaisser" />
                    @endforeach

                    @foreach($a['unsignedContracts'] as $contract)
                        <x-action-row
                            tone="sky"
                            icon="document"
                            :title="$contract->booking->client_name.' · contrat '.$contract->number"
                            :meta="'Non signé · événement le '.$contract->booking->starts_at->format('d/m/Y')"
                            :href="route('bookings.contract', $contract->booking)"
                            action="Ouvrir" />
                    @endforeach

                    @foreach($a['guestsPending'] as $row)
                        <x-action-row
                            tone="violet"
                            icon="users"
                            :title="$row['booking']->client_name.' · '.$row['booking']->reference"
                            :meta="$row['count'].' invité(s) sans réponse · événement dans moins de 3 jours'"
                            :href="route('bookings.guests', $row['booking'])"
                            action="Relancer" />
                    @endforeach
                </ul>
            @endif
        </x-card>

        {{-- Prochains événements --}}
        <x-card flush>
            <x-slot:header>Prochains événements · 7 jours</x-slot:header>

            @forelse($data['upcoming'] as $booking)
                <a href="{{ route('bookings.quote', $booking) }}" wire:navigate
                   class="group flex items-center gap-4 px-5 py-3.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60">
                    <div class="w-12 shrink-0 text-center">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">{{ $booking->starts_at->locale('fr')->isoFormat('ddd') }}</p>
                        <p class="text-xl font-semibold leading-tight">{{ $booking->starts_at->format('d') }}</p>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-900 dark:text-slate-100">
                            {{ $booking->event_type }} · {{ $booking->client_name }}
                        </p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                            {{ $booking->space->name }} · {{ $booking->starts_at->format('H:i') }} → {{ $booking->ends_at->format('d/m H:i') }} · {{ $booking->guests_count }} invités
                        </p>
                    </div>

                    <x-status-badge :status="$booking->status" />
                </a>
            @empty
                <p class="px-5 py-10 text-center text-sm text-slate-500">Aucun événement dans les 7 prochains jours.</p>
            @endforelse
        </x-card>
    </div>

    {{-- Colonne latérale --}}
    <div class="space-y-6">

        {{-- Encaissements --}}
        <x-card>
            <x-slot:header>Encaissements · 6 mois</x-slot:header>

            <div class="flex h-48 items-end gap-3">
                @foreach($data['revenueTrend'] as $month)
                    <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-[10px] font-medium text-slate-500">
                            {{ $month['amount'] > 0 ? number_format($month['amount'] / 1000, 0, ',', ' ').'k' : '' }}
                        </span>
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-amber-500 to-amber-300 transition-all"
                             style="height: {{ max(3, round($month['height'] * 1.4)) }}px"></div>
                        <span class="text-xs capitalize text-slate-500">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-card>

        {{-- Occupation --}}
        <x-card>
            <x-slot:header>Occupation par espace · 30 jours</x-slot:header>

            <ul class="space-y-4">
                @forelse($data['occupancy']['spaces'] as $space)
                    <li>
                        <div class="mb-1.5 flex items-center justify-between text-sm">
                            <span class="font-medium">{{ $space['name'] }}</span>
                            <span class="tabular-nums text-slate-500">{{ $space['percent'] }} %</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                            <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-600 transition-all"
                                 style="width: {{ $space['percent'] }}%"></div>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Aucun espace actif.</li>
                @endforelse
            </ul>
        </x-card>

        {{-- Derniers encaissements --}}
        <x-card flush>
            <x-slot:header>Derniers encaissements</x-slot:header>

            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($data['recentPayments'] as $payment)
                    <li class="flex items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $payment->booking->client_name }}</p>
                            <p class="truncate text-xs text-slate-500">
                                {{ $payment->booking->reference }} · {{ config('venue.payment_methods.'.$payment->method, $payment->method) }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-semibold tabular-nums {{ $payment->amount < 0 ? 'text-red-600 dark:text-red-400' : '' }}">
                                {{ $venue->money($payment->amount) }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $payment->paid_at->diffForHumans() }}</p>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-500">Aucun encaissement pour le moment.</li>
                @endforelse
            </ul>
        </x-card>
    </div>
</div>
</x-app-layout>