@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
    $badge = 'ml-2 rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600 dark:bg-slate-700 dark:text-slate-300';
    $tabBase = 'rounded-xl px-4 py-2 text-sm font-medium';
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Catalogue</h1>
        <p class="text-sm text-slate-500">Espaces, types d'événements et forfaits proposés aux commerciaux. Un élément archivé n'apparaît plus dans les nouvelles réservations.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach(['spaces' => 'Espaces', 'types' => 'Types d\'événements', 'packages' => 'Forfaits'] as $key => $title)
            <button wire:click="$set('tab', '{{ $key }}')"
                    class="{{ $tabBase }} {{ $tab === $key ? 'bg-amber-500 text-white' : 'bg-white/80 dark:bg-slate-800' }}">
                {{ $title }}
            </button>
        @endforeach
    </div>

    {{-- ESPACES --}}
    @if($tab === 'spaces')
        <div class="grid gap-6 md:grid-cols-3">
            <form wire:submit="saveSpace" class="{{ $card }} space-y-3 md:col-span-1">
                <h2 class="font-semibold">{{ $spaceForm['id'] ? 'Modifier l\'espace' : 'Nouvel espace' }}</h2>
                <input wire:model="spaceForm.name" placeholder="Nom" class="{{ $field }}">
                @error('spaceForm.name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                <input type="number" min="1" wire:model="spaceForm.capacity" placeholder="Capacité" class="{{ $field }}">
                @error('spaceForm.capacity') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                <select wire:model="spaceForm.zone" class="{{ $field }}">
                    @foreach($zones as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                </select>
                <button class="w-full rounded-xl bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600">Enregistrer</button>
                @if($spaceForm['id'])
                    <button type="button" wire:click="resetSpaceForm" class="w-full text-sm text-slate-500 hover:underline">Annuler la modification</button>
                @endif
            </form>

            <div class="{{ $card }} overflow-x-auto p-0 md:col-span-2">
                @error('spaces') <p class="p-3 text-sm text-red-500">{{ $message }}</p> @enderror
                <table class="w-full text-sm">
                    <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                        <tr><th class="px-4 py-2">Espace</th><th class="px-4 py-2">Zone</th><th class="px-4 py-2 text-right">Capacité</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($spaces as $space)
                            <tr class="border-t border-slate-200/70 dark:border-slate-800 {{ $space->archived_at ? 'opacity-60' : '' }}">
                                <td class="px-4 py-2">{{ $space->name }} @if($space->archived_at)<span class="{{ $badge }}">Archivé</span>@endif</td>
                                <td class="px-4 py-2 text-slate-500">{{ $zones[$space->zone] ?? $space->zone }}</td>
                                <td class="px-4 py-2 text-right">{{ $space->capacity }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">
                                    <button wire:click="editSpace({{ $space->id }})" class="text-sm text-amber-600 hover:underline">Modifier</button>
                                    @if($space->archived_at)
                                        <button wire:click="restoreSpace({{ $space->id }})" class="ml-2 text-sm text-emerald-600 hover:underline">Réactiver</button>
                                    @else
                                        <button wire:click="archiveSpace({{ $space->id }})" wire:confirm="Archiver cet espace ?" class="ml-2 text-sm text-red-500 hover:underline">Archiver</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-3 text-slate-500">Aucun espace.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- TYPES D'ÉVÉNEMENTS --}}
    @if($tab === 'types')
        <div class="grid gap-6 md:grid-cols-3">
            <form wire:submit="saveType" class="{{ $card }} space-y-3 md:col-span-1">
                <h2 class="font-semibold">{{ $typeForm['id'] ? 'Renommer le type' : 'Nouveau type' }}</h2>
                <input wire:model="typeForm.name" placeholder="Ex : Cérémonie de dot" class="{{ $field }}">
                @error('typeForm.name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                <button class="w-full rounded-xl bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600">Enregistrer</button>
                @if($typeForm['id'])
                    <button type="button" wire:click="resetTypeForm" class="w-full text-sm text-slate-500 hover:underline">Annuler la modification</button>
                @endif
                <p class="text-xs text-slate-500">Renommer un type ne change pas les réservations passées.</p>
            </form>

            <div class="{{ $card }} overflow-x-auto p-0 md:col-span-2">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                        <tr><th class="px-4 py-2">Type</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                            <tr class="border-t border-slate-200/70 dark:border-slate-800 {{ $type->archived_at ? 'opacity-60' : '' }}">
                                <td class="px-4 py-2">{{ $type->name }} @if($type->archived_at)<span class="{{ $badge }}">Archivé</span>@endif</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">
                                    <button wire:click="editType({{ $type->id }})" class="text-sm text-amber-600 hover:underline">Renommer</button>
                                    @if($type->archived_at)
                                        <button wire:click="restoreType({{ $type->id }})" class="ml-2 text-sm text-emerald-600 hover:underline">Réactiver</button>
                                    @else
                                        <button wire:click="archiveType({{ $type->id }})" wire:confirm="Archiver ce type ?" class="ml-2 text-sm text-red-500 hover:underline">Archiver</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-3 text-slate-500">Aucun type.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- FORFAITS --}}
    @if($tab === 'packages')
        <div class="grid gap-6 md:grid-cols-3">
            <form wire:submit="savePackage" class="{{ $card }} space-y-3 md:col-span-1">
                <h2 class="font-semibold">{{ $packageForm['id'] ? 'Modifier le forfait' : 'Nouveau forfait' }}</h2>
                <input wire:model="packageForm.name" placeholder="Nom du forfait" class="{{ $field }}">
                @error('packageForm.name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                <select wire:model="packageForm.type" class="{{ $field }}">
                    @foreach($packageTypes as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                </select>
                <select wire:model="packageForm.time_type" class="{{ $field }}">
                    @foreach($timeTypes as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                </select>

                <div class="grid grid-cols-2 gap-2">
                    <input type="number" min="1" wire:model="packageForm.days" placeholder="Jours" class="{{ $field }}">
                    <input type="number" min="1" wire:model="packageForm.included_hours" placeholder="Heures incluses" class="{{ $field }}">
                    <input type="number" min="0" wire:model="packageForm.base_price" placeholder="Prix" class="{{ $field }}">
                    <input type="number" min="0" wire:model="packageForm.overtime_rate" placeholder="Heure sup." class="{{ $field }}">
                </div>
                @error('packageForm.base_price') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                <div>
                    <p class="mb-1 text-xs font-medium text-slate-600 dark:text-slate-300">Espaces concernés (aucun coché = tous)</p>
                    <div class="grid gap-1">
                        @foreach($allSpaces as $space)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" wire:model="packageForm.space_ids" value="{{ $space->id }}" class="rounded">
                                {{ $space->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button class="w-full rounded-xl bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600">Enregistrer</button>
                @if($packageForm['id'])
                    <button type="button" wire:click="resetPackageForm" class="w-full text-sm text-slate-500 hover:underline">Annuler la modification</button>
                @endif
                <p class="text-xs text-slate-500">Une modification de prix ne change pas les devis déjà émis.</p>
            </form>

            <div class="{{ $card }} overflow-x-auto p-0 md:col-span-2">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                        <tr><th class="px-4 py-2">Forfait</th><th class="px-4 py-2">Espaces</th><th class="px-4 py-2 text-right">Prix</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr class="border-t border-slate-200/70 align-top dark:border-slate-800 {{ $package->archived_at ? 'opacity-60' : '' }}">
                                <td class="px-4 py-2">
                                    {{ $package->name }} @if($package->archived_at)<span class="{{ $badge }}">Archivé</span>@endif
                                    <p class="text-xs text-slate-500">{{ $packageTypes[$package->type] }} · {{ $timeTypes[$package->time_type] }}</p>
                                </td>
                                <td class="px-4 py-2 text-slate-500">{{ $package->spaces->isEmpty() ? 'Tous' : $package->spaces->pluck('name')->implode(', ') }}</td>
                                <td class="px-4 py-2 text-right">{{ $venue->money($package->base_price) }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">
                                    <button wire:click="editPackage({{ $package->id }})" class="text-sm text-amber-600 hover:underline">Modifier</button>
                                    @if($package->archived_at)
                                        <button wire:click="restorePackage({{ $package->id }})" class="ml-2 text-sm text-emerald-600 hover:underline">Réactiver</button>
                                    @else
                                        <button wire:click="archivePackage({{ $package->id }})" wire:confirm="Archiver ce forfait ?" class="ml-2 text-sm text-red-500 hover:underline">Archiver</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-3 text-slate-500">Aucun forfait.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>