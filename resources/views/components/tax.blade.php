<div>

<div class="min-h-screen bg-base-200 py-12">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-success">
                Profil Fiscal
            </h1>
            <p class="text-base-content/60 mt-2">
                Configurez votre infrastructure fiscale intelligente
            </p>
        </div>

        <!-- Card -->
        <div class="card bg-base-100 shadow-2xl border border-success/20">
            <div class="card-body">

                <form method="POST" action="" class="space-y-8" wire:submit.prevent="save">
                    @csrf

                    <!-- Filing Status -->
                    <div class="form-control">
                        <label class="label mr-3">
                            <span class="label-text font-semibold text-success">
                                Statut Fiscal
                            </span>
                        </label>

                        <select wire:model.live="filing_status" class="select select-bordered focus:select-success">
                            <option value="single">Célibataire</option>
                            <option value="married_joint">Marié (Déclaration commune)</option>
                            <option value="married_separeted">Marié (Déclaration séparée)</option>
                            <option value="head_household">Chef de ménage</option>
                        </select>
                    </div>

                    <!-- Income Section -->
                    <div class="divider text-success font-bold">Revenus</div>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    Revenu annuel (€)
                                </span>
                            </label>
                            <input type="number" step="0.01" wire:model.live="property"="annual_income"
                                class="input input-bordered focus:input-success"
                                placeholder="0.00">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    Revenu activité commerciale (€)
                                </span>
                            </label>
                            <input type="number" step="0.01" wire:model.live="property"="business_income"
                                class="input input-bordered focus:input-success"
                                placeholder="0.00">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    Autres revenus (€)
                                </span>
                            </label>
                            <input type="number" step="0.01" wire:model.live="property"="other_income"
                                class="input input-bordered focus:input-success"
                                placeholder="0.00">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    Nombre de personnes à charge
                                </span>
                            </label>
                            <input type="number" wire:model.live="property"="depends"
                                class="input input-bordered focus:input-success"
                                placeholder="0">
                        </div>

                    </div>

                    <!-- Location Section -->
                    <div class="divider text-success font-bold">Localisation</div>

                    <div class="grid md:grid-cols-3 gap-6">

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Pays</span>
                            </label>
                                    <select wire:model.live="selectedCountry"
                class="select select-bordered select-success">
                    <option value="">Choisir un pays</option>

                        @foreach($countries as $country)
                            <option value="{{ $country['id'] }}" >
                                {{ $country['name'] }}
                            </option>
                        @endforeach
                     </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">État / Province</span>
                            </label>
                                    <select wire:model.live="selectedState"
                class="select select-bordered select-success"
                @disabled(!$states)>
                    <option value="">Choisir une province</option>

                    @foreach($states as $state)
                        <option value="{{ $state['id'] }}">
                            {{ $state['name'] }}
                        </option>
                    @endforeach
        </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Ville</span>
                            </label>
                                    <select wire:model.live="selectedCity"
                class="select select-bordered select-success"
                @disabled(!$cities)>
            <option value="">Choisir une ville</option>

                        @foreach($cities as $city)
                            <option value="{{ $city['name'] }}">
                                {{ $city['name'] }}
                            </option>
                        @endforeach
        </select>
                        </div>

                    </div>

                    <!-- Crypto Section -->
                    <div class="divider text-success font-bold">Activité Numérique</div>

                    <div class="form-control">
                        <label class="cursor-pointer flex items-center gap-4">
                            <input type="checkbox" wire:model.live="crypto_activity"
                                class="toggle toggle-success" @if($crypto_activity) checked @endif>
                            <span class="label-text font-semibold">
                                J'ai une activité crypto
                            </span>
                        </label>
                    </div>
                    <label class="label">
        <span class="label-text-alt text-base-content/50">
            Cela inclut le trading, le staking ou les paiements reçus en crypto.
        </span>
    </label>

                    <!-- Submit -->
                    <div class="pt-6">
                        <button type="submit"
                            class="btn btn-success btn-block text-white text-lg shadow-lg hover:scale-105 transition duration-300">
                            Enregistrer le profil fiscal
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <p class="text-center mt-8 text-sm text-base-content/50 italic">
            Vos données sont stockées de manière sécurisée et chiffrée.
        </p>

    </div>
</div>
</div>
