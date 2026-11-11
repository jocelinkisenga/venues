@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
    $venue = $booking->venue;
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">Devis {{ $booking->reference }}</h1>
            <p class="text-sm text-slate-500">{{ $booking->client_name }} · {{ $booking->event_type }} · {{ $booking->guests_count }} invités · {{ $booking->status->label() }}</p>
        </div>
        <div class="flex flex-wrap gap-2 text-sm">
            <a href="{{ route('bookings.seating', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Plan de salle</a>
            <a href="{{ route('bookings.beo', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">BEO</a>
            <a href="{{ route("pdf.quote", $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">PDF devis</a>

            <a href="{{ route('bookings.deposit', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Caution</a>
<a href="{{ route('bookings.cancel', $booking) }}" class="rounded-lg bg-red-50 px-3 py-2 text-red-600 shadow-sm dark:bg-red-900/30">Annuler</a>
<a href="{{ route('bookings.logistics', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Logistique</a>
<a href="{{ route('bookings.staff', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Équipe</a>
<a href="{{ route('bookings.contract', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Contrat</a>
<a href="{{ route('bookings.guests', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Invités</a>
<a href="{{ route('bookings.checkin', $booking) }}" class="rounded-lg bg-white/80 px-3 py-2 shadow-sm dark:bg-slate-800">Entrée</a>
            <a href="{{ $whatsappUrl }}" target="_blank" class="rounded-lg bg-emerald-500 px-3 py-2 font-medium text-white shadow-sm">Envoyer par WhatsApp</a>
        </div>
    </div>

    @if(session('success')) <p class="text-sm text-emerald-600">{{ session('success') }}</p> @endif

    <section class="{{ $card }}">
        <h2 class="mb-3 font-semibold">Prestations</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-xs uppercase text-slate-500">
                    <tr><th class="py-2">Libellé</th><th>Partenaire</th><th class="text-right">Qté</th><th class="text-right">PU</th><th class="text-right">Total</th><th class="text-right">Commission</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($quote->lines as $line)
                        <tr class="border-t border-slate-200/70 dark:border-slate-800">
                            <td class="py-2">{{ $line->label }}</td>
                            <td>{{ $line->partner?->name ?? '—' }}</td>
                            <td class="text-right">{{ rtrim(rtrim($line->quantity, '0'), '.') }}</td>
                            <td class="text-right">{{ $venue->money($line->unit_price) }}</td>
                            <td class="text-right font-medium">{{ $venue->money($line->line_total) }}</td>
                            <td class="text-right text-slate-500">{{ $venue->money($line->commission_amount) }}</td>
                            <td class="text-right"><button wire:click="removeLine({{ $line->id }})" class="text-red-500 hover:underline">Retirer</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-3 text-slate-500">Aucune prestation.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form wire:submit="addLine" class="mt-4 grid gap-3 md:grid-cols-6">
            <input wire:model="label" placeholder="Libellé (ex : Traiteur buffet)" class="{{ $field }} md:col-span-2">
            <input type="number" step="0.01" wire:model="quantity" placeholder="Qté" class="{{ $field }}">
            <input type="number" step="1" wire:model="unit_price" placeholder="Prix unitaire" class="{{ $field }}">
            <select wire:model="partner_id" class="{{ $field }}">
                <option value="">Sans partenaire</option>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->commission_percent }} %)</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Ajouter</button>
            @error('label') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
        </form>
    </section>

    <div class="grid gap-6 md:grid-cols-2">
        <section class="{{ $card }}">
            <h2 class="mb-3 font-semibold">Remise commerciale</h2>
            <form wire:submit="applyDiscount" class="grid gap-3 sm:grid-cols-3">
                <select wire:model.live="discount_type" class="{{ $field }}">
                    <option value="none">Aucune</option>
                    <option value="percent">En %</option>
                    <option value="fixed">Montant fixe</option>
                </select>
                <input type="number" step="0.01" wire:model="discount_value" class="{{ $field }}">
                <button class="rounded-xl bg-slate-800 px-4 py-2 font-medium text-white dark:bg-slate-200 dark:text-slate-900">Appliquer</button>
                @if($discount_type !== 'none')
                    <input wire:model="discount_reason" placeholder="Motif (obligatoire)" class="{{ $field }} sm:col-span-3">
                @endif
                @error('discount_reason') <p class="text-sm text-red-500 sm:col-span-3">{{ $message }}</p> @enderror
                @error('discount_value') <p class="text-sm text-red-500 sm:col-span-3">{{ $message }}</p> @enderror
            </form>
        </section>

        <section class="{{ $card }}">
            <h2 class="mb-3 font-semibold">Récapitulatif</h2>
            <dl class="space-y-1 text-sm">
                <div class="flex justify-between"><dt>Sous-total</dt><dd>{{ $venue->money($quote->subtotal) }}</dd></div>
                @if($quote->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600"><dt>Remise ({{ $quote->discount_reason }})</dt><dd>- {{ $venue->money($quote->discount_amount) }}</dd></div>
                @endif
                <div class="flex justify-between"><dt>TVA ({{ rtrim(rtrim($venue->tax_rate, '0'), '.') }} %)</dt><dd>{{ $venue->money($quote->tax_amount) }}</dd></div>
                <div class="flex justify-between text-base font-bold"><dt>Total</dt><dd>{{ $venue->money($quote->total) }}</dd></div>
                <div class="flex justify-between text-amber-600"><dt>Acompte ({{ $venue->deposit_percent }} %)</dt><dd>{{ $venue->money($deposit) }}</dd></div>
                <div class="flex justify-between"><dt>Encaissé</dt><dd>{{ $venue->money($paid) }}</dd></div>
                <div class="flex justify-between font-semibold"><dt>Solde restant</dt><dd>{{ $venue->money($balance) }}</dd></div>
                <div class="flex justify-between text-xs text-slate-500"><dt>Commissions partenaires</dt><dd>{{ $venue->money($quote->commission_total) }}</dd></div>
            </dl>
        </section>
    </div>

    <section class="{{ $card }}">
        <h2 class="mb-3 font-semibold">Encaissement</h2>
        <form wire:submit="recordPayment" class="grid gap-3 md:grid-cols-5">
            <input type="number" step="1" wire:model="payment_amount" placeholder="Montant" class="{{ $field }}">
            <select wire:model="payment_kind" class="{{ $field }}">
                <option value="deposit">Acompte</option>
                <option value="balance">Solde</option>
                <option value="other">Autre</option>
            </select>
            <select wire:model="payment_method" class="{{ $field }}">
                @foreach($paymentMethods as $key => $label)
                    <option value="{{ $key }}">{{ \Illuminate\Support\Str::before($label, ' (') }}</option>
                @endforeach
            </select>
            <input wire:model="payment_reference" placeholder="Réf. transaction" class="{{ $field }}">
            <button class="rounded-xl bg-emerald-600 px-4 py-2 font-semibold text-white hover:bg-emerald-700">Enregistrer</button>
            @error('payment_amount') <p class="text-sm text-red-500 md:col-span-5">{{ $message }}</p> @enderror
        </form>
        <p class="mt-3 text-xs text-slate-500">Un acompte suffisant confirme automatiquement l'option.</p>
    </section>
</div>
