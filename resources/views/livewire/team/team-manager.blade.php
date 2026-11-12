<div>
<div>
    {{-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh --}}
</div>
@php
    $field = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-400/20 dark:border-slate-700 dark:bg-slate-900';
    $label = 'mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300';
    $option = 'flex cursor-pointer gap-3 rounded-xl border border-slate-200 p-3 transition hover:bg-slate-50 has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50/60 dark:border-slate-700 dark:hover:bg-slate-800 dark:has-[:checked]:bg-amber-400/10';
@endphp

<div class="space-y-6">
    <x-page-header
        title="Équipe"
        subtitle="Ajoutez les commerciaux et l'équipe technique. Chaque membre n'accède qu'aux pages de son rôle." />

    @if($notice)
        <p class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">{{ $notice }}</p>
    @endif

    @error('team')
        <p class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-400/10 dark:text-red-300">{{ $message }}</p>
    @enderror

    @if($temporaryPassword)
        <x-card class="border-amber-300 bg-amber-50/70 dark:border-amber-400/40 dark:bg-amber-400/10">
            <p class="text-sm font-semibold text-slate-900 dark:text-white">Mot de passe provisoire pour {{ $temporaryFor }}</p>
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <code class="rounded-lg bg-white px-3 py-2 font-mono text-base tracking-wider text-slate-900 shadow-sm dark:bg-slate-900 dark:text-white">{{ $temporaryPassword }}</code>
                <x-button variant="secondary" type="button"
                          x-data="{ copied: false }"
                          x-on:click="navigator.clipboard.writeText('{{ $temporaryPassword }}'); copied = true; setTimeout(() => copied = false, 2000)">
                    <span x-show="!copied">Copier</span>
                    <span x-show="copied" x-cloak>Copié</span>
                </x-button>
                <x-button variant="ghost" wire:click="dismissPassword">Fermer</x-button>
            </div>
            <p class="mt-3 text-xs text-slate-600 dark:text-slate-400">
                Ce mot de passe ne sera plus affiché. Transmettez-le de façon sécurisée, puis demandez au membre de le conserver précieusement.
            </p>
        </x-card>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Ajout d'un membre --}}
        <x-card class="lg:col-span-1">
            <x-slot:header>Ajouter un membre</x-slot:header>

            <form wire:submit="add" class="space-y-4">
                <div>
                    <label for="name" class="{{ $label }}">Nom complet</label>
                    <input id="name" wire:model="name" class="{{ $field }}" placeholder="Ex : Marie Lukusa">
                    @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="{{ $label }}">Adresse email</label>
                    <input id="email" type="email" wire:model="email" class="{{ $field }}" placeholder="nom@exemple.com">
                    <p class="mt-1 text-xs text-slate-500">Sert d'identifiant de connexion.</p>
                    @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="{{ $label }}">Rôle</label>
                    <select id="role" wire:model="role" class="{{ $field }}">
                        @foreach($roleOptions as $r)
                            <option value="{{ $r->value }}">{{ $r->label() }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <fieldset class="space-y-2">
                    <legend class="{{ $label }}">Mode d'ajout</legend>

                    <label class="{{ $option }}">
                        <input type="radio" wire:model.live="mode" value="invite" class="mt-1 text-amber-500 focus:ring-amber-400">
                        <span>
                            <span class="block text-sm font-medium">Invitation par email</span>
                            <span class="block text-xs text-slate-500">Le membre reçoit un lien pour choisir son mot de passe.</span>
                        </span>
                    </label>

                    <label class="{{ $option }}">
                        <input type="radio" wire:model.live="mode" value="password" class="mt-1 text-amber-500 focus:ring-amber-400">
                        <span>
                            <span class="block text-sm font-medium">Mot de passe provisoire</span>
                            <span class="block text-xs text-slate-500">Pour un membre sans adresse email personnelle. Affiché une seule fois.</span>
                        </span>
                    </label>
                </fieldset>

                <x-button type="submit" class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $mode === 'invite' ? 'Envoyer l\'invitation' : 'Créer le compte' }}</span>
                    <span wire:loading>Enregistrement…</span>
                </x-button>
            </form>
        </x-card>

        {{-- Liste des membres --}}
        <x-card flush class="lg:col-span-2">
            <x-slot:header>Membres · {{ $members->count() }}</x-slot:header>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:bg-slate-800/60">
                        <tr>
                            <th class="px-5 py-2.5 font-medium">Membre</th>
                            <th class="px-5 py-2.5 font-medium">Rôle</th>
                            <th class="px-5 py-2.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($members as $member)
                            <tr wire:key="member-{{ $member->id }}">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-xs font-semibold text-white">
                                            {{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-slate-900 dark:text-slate-100">
                                                {{ $member->name }}
                                                @if($member->id === $me)
                                                    <span class="ml-1 text-xs font-normal text-slate-400">(vous)</span>
                                                @endif
                                            </p>
                                            <p class="truncate text-xs text-slate-500">{{ $member->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-3">
                                    <select wire:model.live="memberRoles.{{ $member->id }}"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm shadow-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/30 dark:border-slate-700 dark:bg-slate-900">
                                        @foreach($roleOptions as $r)
                                            <option value="{{ $r->value }}">{{ $r->label() }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-5 py-3 text-right">
                                    @if($member->id !== $me)
                                        <button type="button"
                                                wire:click="remove({{ $member->id }})"
                                                wire:confirm="Retirer {{ $member->name }} de cette salle ?"
                                                class="text-sm font-medium text-red-600 transition hover:text-red-700 hover:underline dark:text-red-400">
                                            Retirer
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center text-sm text-slate-500">
                                    Aucun membre pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    {{-- Rappel des rôles --}}
    <x-card>
        <x-slot:header>Ce que chaque rôle peut faire</x-slot:header>
        <div class="grid gap-6 text-sm md:grid-cols-3">
            <div>
                <p class="font-semibold text-slate-900 dark:text-white">Propriétaire</p>
                <p class="mt-1 text-slate-500">Accès complet : catalogue, finances, équipe, paramètres et réservations.</p>
            </div>
            <div>
                <p class="font-semibold text-slate-900 dark:text-white">Commercial</p>
                <p class="mt-1 text-slate-500">Réservations, devis, contrats, CRM et encaissements.</p>
            </div>
            <div>
                <p class="font-semibold text-slate-900 dark:text-white">Équipe technique</p>
                <p class="mt-1 text-slate-500">Calendrier, plan de salle, BEO, stock, logistique et contrôle des entrées.</p>
            </div>
        </div>
    </x-card>
</div>
</div>