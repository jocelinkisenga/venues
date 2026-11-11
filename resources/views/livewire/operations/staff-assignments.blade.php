@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div>
        <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
        <h1 class="text-2xl font-bold">Équipe · {{ $booking->reference }}</h1>
        <p class="text-sm text-slate-500">Personnel affecté à l'événement et rémunération prévue.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <form wire:submit="assign" class="{{ $card }} space-y-3">
            <h2 class="font-semibold">Affecter à cet événement</h2>
            <select wire:model="staffId" class="{{ $field }} w-full">
                <option value="">Choisir une personne…</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $roles[$member->role] }})</option>
                @endforeach
            </select>
            @error('staffId') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <select wire:model="assignRole" class="{{ $field }} w-full">
                @foreach($roles as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select>
            <input type="number" min="0" wire:model="assignPay" placeholder="Rémunération pour cet événement" class="{{ $field }} w-full">
            @error('assignPay') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <button class="w-full rounded-xl bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600">Affecter</button>
        </form>

        <form wire:submit="addMember" class="{{ $card }} space-y-3">
            <h2 class="font-semibold">Ajouter une personne à l'équipe</h2>
            <input wire:model="memberName" placeholder="Nom complet" class="{{ $field }} w-full">
            @error('memberName') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <select wire:model="memberRole" class="{{ $field }} w-full">
                @foreach($roles as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select>
            <input wire:model="memberPhone" placeholder="Téléphone / WhatsApp" class="{{ $field }} w-full">
            <input type="number" min="0" wire:model="memberRate" placeholder="Tarif habituel par événement" class="{{ $field }} w-full">
            <button class="w-full rounded-xl bg-slate-800 py-2 font-semibold text-white dark:bg-slate-200 dark:text-slate-900">Ajouter</button>
        </form>
    </div>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Personne</th>
                    <th class="px-4 py-2">Rôle</th>
                    <th class="px-4 py-2 text-right">Rémunération</th>
                    <th class="px-4 py-2">Payé</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2">{{ $a->staffMember->name }}</td>
                        <td class="px-4 py-2">{{ $roles[$a->role] }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($a->pay_amount) }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="togglePaid({{ $a->id }})" class="{{ $a->paid_at ? 'text-emerald-600' : 'text-amber-600' }} hover:underline">
                                {{ $a->paid_at ? 'Payé le '.$a->paid_at->format('d/m') : 'À payer' }}
                            </button>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click="remove({{ $a->id }})" class="text-red-500 hover:underline">Retirer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-slate-500">Personne n'est affecté à cet événement.</td></tr>
                @endforelse
            </tbody>
            @if($assignments->isNotEmpty())
                <tfoot>
                    <tr class="border-t border-slate-200 font-semibold dark:border-slate-700">
                        <td class="px-4 py-2" colspan="2">Total</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($totalPay) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>