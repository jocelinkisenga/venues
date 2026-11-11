@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Accès API et appareils</h1>
        <p class="text-sm text-slate-500">Chaque jeton ne donne accès qu'à cette salle et aux permissions cochées.</p>
    </div>

    @if($plainToken)
        <div class="rounded-2xl border border-amber-300 bg-amber-50 p-5 dark:bg-amber-900/20">
            <p class="mb-2 text-sm font-semibold">Copiez ce jeton maintenant. Il ne sera plus affiché.</p>
            <code class="block break-all rounded-lg bg-white p-3 text-sm dark:bg-slate-900">{{ $plainToken }}</code>
        </div>
    @endif

    <form wire:submit="create" class="{{ $card }} space-y-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1">
                <label class="mb-1 block text-sm font-medium">Nom du jeton</label>
                <input wire:model="name" placeholder="Ex : Tablette entrée" class="{{ $field }}">
                @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <button type="button" wire:click="preset('door')" class="rounded-lg bg-white/80 px-3 py-2 text-sm shadow-sm dark:bg-slate-800">Preset entrée</button>
            <button type="button" wire:click="preset('reporting')" class="rounded-lg bg-white/80 px-3 py-2 text-sm shadow-sm dark:bg-slate-800">Preset lecture</button>
        </div>

        <div class="grid gap-2 sm:grid-cols-2">
            @foreach($labels as $ability => $label)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="abilities" value="{{ $ability }}" class="rounded">
                    {{ $label }} <span class="text-xs text-slate-400">({{ $ability }})</span>
                </label>
            @endforeach
        </div>
        @error('abilities') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Créer le jeton</button>
    </form>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Permissions</th>
                    <th class="px-4 py-2">Dernier usage</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tokens as $token)
                    <tr class="border-t border-slate-200/70 align-top dark:border-slate-800">
                        <td class="px-4 py-2">{{ $token->name }}</td>
                        <td class="px-4 py-2 text-xs text-slate-500">{{ implode(', ', $token->abilities) }}</td>
                        <td class="px-4 py-2">{{ $token->last_used_at?->diffForHumans() ?? 'Jamais' }}</td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click="revoke({{ $token->id }})" wire:confirm="Révoquer ce jeton ?" class="text-red-500 hover:underline">Révoquer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-3 text-slate-500">Aucun jeton pour cette salle.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>