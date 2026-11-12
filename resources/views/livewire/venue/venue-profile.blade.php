@php
    $field = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-400/20 dark:border-slate-700 dark:bg-slate-900';
    $label = 'mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300';
@endphp

<div class="space-y-8">
    <x-page-header
        title="Profil public"
        subtitle="Ces informations apparaissent sur la plateforme, sauf les données administratives marquées confidentielles." />

    @if($notice)
        <p class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">{{ $notice }}</p>
    @endif

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">

            <form wire:submit="save" class="space-y-6">

                <x-card>
                    <x-slot:header>Identité</x-slot:header>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="{{ $label }}">Nom de la salle</label>
                            <input wire:model="name" class="{{ $field }}">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $label }}">Ville</label>
                            <input wire:model="city" class="{{ $field }}">
                        </div>
                        <div>
                            <label class="{{ $label }}">Pays</label>
                            <select wire:model="country" class="{{ $field }}">
                                <option value="CD">République démocratique du Congo</option>
                                <option value="CI">Côte d'Ivoire</option>
                                <option value="SN">Sénégal</option>
                                <option value="CM">Cameroun</option>
                                <option value="KE">Kenya</option>
                                <option value="GN">Guinée</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $label }}">Adresse</label>
                            <input wire:model="address" placeholder="Avenue, quartier, repère" class="{{ $field }}">
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>Contact</x-slot:header>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $label }}">Téléphone de contact</label>
                            <input wire:model="contact_phone" placeholder="+243 81 234 5678" class="{{ $field }}">
                            @error('contact_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $label }}">Email</label>
                            <input type="email" wire:model="email" class="{{ $field }}">
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $label }}">Latitude (optionnel)</label>
                            <input wire:model="latitude" placeholder="-11.6667" class="{{ $field }}">
                            @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $label }}">Longitude (optionnel)</label>
                            <input wire:model="longitude" placeholder="27.4833" class="{{ $field }}">
                            @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>Présentation</x-slot:header>
                    <label class="{{ $label }}">Description</label>
                    <textarea wire:model="description" rows="6"
                              placeholder="Décrivez l'ambiance, l'architecture, les points forts de la salle, les événements accueillis."
                              class="{{ $field }}"></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </x-card>

                <x-card>
                    <x-slot:header>Équipements</x-slot:header>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($amenityOptions as $key => $amenityLabel)
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-3 py-2.5 text-sm transition hover:bg-slate-50 has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50/60 dark:border-slate-700 dark:hover:bg-slate-800 dark:has-[:checked]:bg-amber-400/10">
                                <input type="checkbox" wire:model="amenities" value="{{ $key }}" class="rounded text-amber-500 focus:ring-amber-400">
                                {{ $amenityLabel }}
                            </label>
                        @endforeach
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <span class="flex items-center gap-2">Confidentiel
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">Non affiché publiquement</span>
                        </span>
                    </x-slot:header>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="{{ $label }}">NIF</label>
                            <input wire:model="nif" class="{{ $field }}">
                        </div>
                        <div>
                            <label class="{{ $label }}">RCCM</label>
                            <input wire:model="rccm" class="{{ $field }}">
                        </div>
                        <div>
                            <label class="{{ $label }}">Identifiant national</label>
                            <input wire:model="id_nat" class="{{ $field }}">
                        </div>
                    </div>
                </x-card>

                <div class="flex justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove>Enregistrer le profil</span>
                        <span wire:loading>Enregistrement…</span>
                    </x-button>
                </div>
            </form>

            {{-- Photos : hors du formulaire principal --}}
            <x-card>
                <x-slot:header>Photos · {{ $photos->count() }} / {{ $maxPhotos }}</x-slot:header>

                <form wire:submit="uploadPhotos" class="space-y-3">
                    <label class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 px-6 py-8 text-center transition hover:border-amber-400 hover:bg-amber-50/40 dark:border-slate-700 dark:hover:bg-amber-400/5">
                        <x-ui-icon name="plus" class="h-6 w-6 text-slate-400" />
                        <span class="text-sm font-medium">Choisir des photos</span>
                        <span class="text-xs text-slate-500">JPG, PNG ou WebP · 4 Mo maximum par photo</span>
                        <input type="file" wire:model="newPhotos" multiple accept="image/jpeg,image/png,image/webp" class="sr-only">
                    </label>

                    @error('newPhotos') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('newPhotos.*') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

                    @if(! empty($newPhotos))
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-slate-500">{{ count($newPhotos) }} photo(s) sélectionnée(s)</p>
                            <x-button type="submit" wire:loading.attr="disabled">Ajouter</x-button>
                        </div>
                    @endif
                </form>

                @if($photos->isNotEmpty())
                    <ul class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($photos as $photo)
                            <li wire:key="photo-{{ $photo->id }}" class="group relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                                <img src="{{ $photo->url() }}" alt="" class="aspect-square w-full object-cover">

                                @if($loop->first)
                                    <span class="absolute left-2 top-2 rounded-full bg-amber-500 px-2 py-0.5 text-[11px] font-semibold text-white shadow">Principale</span>
                                @endif

                                <div class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-2 bg-gradient-to-t from-black/70 to-transparent p-2 opacity-0 transition group-hover:opacity-100">
                                    @unless($loop->first)
                                        <button type="button" wire:click="setCover({{ $photo->id }})" class="rounded-lg bg-white/90 px-2 py-1 text-xs font-medium text-slate-900">Principale</button>
                                    @else
                                        <span></span>
                                    @endunless
                                    <button type="button" wire:click="deletePhoto({{ $photo->id }})" wire:confirm="Supprimer cette photo ?"
                                            class="rounded-lg bg-red-600 px-2 py-1 text-xs font-medium text-white">Supprimer</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-6 text-center text-sm text-slate-500">Aucune photo. Ajoutez au moins une photo pour publier la salle.</p>
                @endif
            </x-card>
        </div>

        {{-- Publication --}}
        <aside class="space-y-6 xl:sticky xl:top-24 xl:h-fit">
            <x-card>
                <x-slot:header>Publication</x-slot:header>

                <ul class="space-y-2.5 text-sm">
                    @foreach($checklist as $item => $ok)
                        <li class="flex items-start gap-2.5">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $ok ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-400/15 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }}">
                                @if($ok)
                                    <x-ui-icon name="check" class="h-3.5 w-3.5" />
                                @else
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                @endif
                            </span>
                            <span class="{{ $ok ? 'text-slate-700 dark:text-slate-300' : 'text-slate-500' }}">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 border-t border-slate-200/70 pt-5 dark:border-slate-800">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" wire:model="is_listed" form="" class="mt-1 rounded text-amber-500 focus:ring-amber-400">
                        <span>
                            <span class="block text-sm font-medium">Publier la salle sur la plateforme</span>
                            <span class="block text-xs text-slate-500">Visible par tous, avec possibilité de demande en ligne.</span>
                        </span>
                    </label>
                    @error('is_listed') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                @if($publicUrl)
                    <div class="mt-5 rounded-xl bg-slate-50 p-3 dark:bg-slate-800/60">
                        <p class="text-xs font-medium text-slate-500">Adresse publique</p>
                        <a href="{{ $publicUrl }}" target="_blank" class="mt-1 block truncate text-sm font-medium text-amber-700 hover:underline dark:text-amber-400">{{ $publicUrl }}</a>
                    </div>
                @endif
            </x-card>
        </aside>
    </div>
</div>