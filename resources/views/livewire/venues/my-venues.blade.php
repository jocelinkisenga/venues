@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Mes salles</h1>
        <p class="text-sm text-slate-500">Passez d'une salle à l'autre. Chaque salle a ses réservations, son stock et son équipe.</p>
    </div>

    @if(session('success'))
        <p class="rounded-xl bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('success') }}</p>
    @endif
    @error('switch') <p class="rounded-xl bg-red-50 p-3 text-sm text-red-600">{{ $message }}</p> @enderror

    <div class="grid gap-3 md:grid-cols-2">
        @foreach($venues as $venue)
            <div class="{{ $card }} flex items-center justify-between gap-3">
                <div>
                    <p class="font-semibold">{{ $venue->name }}
                        @if($current && $current->id === $venue->id)
                            <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Active</span>
                        @endif
                        @if($venue->isSuspended())
                            <span class="ml-1 rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-700">Suspendue</span>
                        @endif
                    </p>
                    <p class="text-xs text-slate-500">{{ $venue->city }} · {{ $venue->currency }} · {{ \App\Enums\Role::from($venue->pivot->role)->label() }}</p>
                </div>
                @if(! $current || $current->id !== $venue->id)
                    <button wire:click="switchTo({{ $venue->id }})" class="rounded-lg bg-slate-800 px-3 py-2 text-sm text-white dark:bg-slate-200 dark:text-slate-900">Ouvrir</button>
                @endif
            </div>
        @endforeach
    </div>

    <form wire:submit="create" class="{{ $card }} grid gap-3 md:grid-cols-6">
        <h2 class="font-semibold md:col-span-6">Créer une nouvelle salle</h2>
        <input wire:model="name" placeholder="Nom de la salle" class="{{ $field }} md:col-span-2">
        <input wire:model="city" placeholder="Ville" class="{{ $field }}">
        <select wire:model="currency" class="{{ $field }}">
            @foreach($currencies as $code => $meta)
                <option value="{{ $code }}">{{ $code }}</option>
            @endforeach
        </select>
        <select wire:model="country" class="{{ $field }}">
            <option value="CD">RDC</option>
            <option value="CI">Côte d'Ivoire</option>
            <option value="SN">Sénégal</option>
            <option value="CM">Cameroun</option>
            <option value="KE">Kenya</option>
        </select>
        <input wire:model="timezone" placeholder="Fuseau horaire" class="{{ $field }}">
        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600 md:col-span-6">Créer et ouvrir</button>
        @error('name') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
        @error('timezone') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
    </form>

    @if($isOwner)
        <div class="grid gap-6 md:grid-cols-2">
            <form wire:submit="invite" class="{{ $card }} space-y-3">
                <h2 class="font-semibold">Inviter un membre dans {{ $current->name }}</h2>
                <input type="email" wire:model="inviteEmail" placeholder="Adresse email" class="{{ $field }}">
                @error('inviteEmail') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                <select wire:model="inviteRole" class="{{ $field }}">
                    <option value="commercial">Commercial</option>
                    <option value="technique">Équipe technique</option>
                    <option value="proprietaire">Propriétaire</option>
                </select>
                <button class="w-full rounded-xl bg-slate-800 py-2 font-semibold text-white dark:bg-slate-200 dark:text-slate-900">Envoyer l'invitation</button>
            </form>

            <div class="{{ $card }} overflow-x-auto p-0">
                @error('team') <p class="p-3 text-sm text-red-500">{{ $message }}</p> @enderror
                <table class="w-full text-sm">
                    <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                        <tr><th class="px-4 py-2">Membre</th><th class="px-4 py-2">Rôle</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($team as $member)
                            <tr class="border-t border-slate-200/70 dark:border-slate-800">
                                <td class="px-4 py-2">{{ $member->name }}<br><span class="text-xs text-slate-500">{{ $member->email }}</span></td>
                                <td class="px-4 py-2">{{ \App\Enums\Role::from($member->pivot->role)->label() }}</td>
                                <td class="px-4 py-2 text-right">
                                    @if($member->id !== auth()->id())
                                        <button wire:click="removeMember({{ $member->id }})" wire:confirm="Retirer ce membre de la salle ?" class="text-red-500 hover:underline">Retirer</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>