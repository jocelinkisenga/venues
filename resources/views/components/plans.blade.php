  @php
  use App\Models\Plan;
  @endphp      
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            @foreach(Plan::all() as $plan)
            @if($plan->slug == 'standard')
            <div class="card bg-base-100 shadow-xl border border-base-300 hover:border-primary transition-all">
                <div class="card-body gap-6">
                    <div>
                        <h3 class="text-2xl font-bold">Standard</h3>
                        <p class="text-base-content/60 text-sm">Pour un besoin fiscal précis</p>
                    </div>
                    
                    <div class="flex items-baseline">
                        <span class="text-5xl font-extrabold">49€</span>
                        <span class="ml-1 text-xl font-medium text-base-content/50">/rapport</span>
                    </div>

                    <ul class="space-y-4 my-4">
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Rapport d'IA structuré (JSON) </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Validation humaine incluse </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Stockage S3 chiffré (Privé) </span>
                        </li>
                        <li class="flex items-start gap-3 opacity-50">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Tableau de bord expert (Filament)</span>
                        </li>
                    </ul>

                    <div class="card-actions">
                        <button class="btn btn-outline btn-block">Démarrer une analyse</button>
                    </div>
                </div>
            </div>

            @elseif($plan->slug  == 'business-ai')
                        <div class="card bg-base-100 shadow-2xl border-2 border-success relative overflow-hidden scale-105">
                <div class="bg-success text-white-content text-center py-1 text-sm font-bold uppercase tracking-widest">
                    Recommandé
                </div>
                <div class="card-body gap-6">
                    <div>
                        <h3 class="text-2xl font-bold">Business AI</h3>
                        <p class="text-base-content/60 text-sm">Idéal pour les cabinets de conseil [cite: 2]</p>
                    </div>

                    <div class="flex items-baseline">
                        <span class="text-5xl font-extrabold">199€</span>
                        <span class="ml-1 text-xl font-medium text-base-content/50">/mois</span>
                    </div>

                    <ul class="space-y-4 my-4">
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span class="font-semibold">Jusqu'à 15 analyses mensuelles</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Accès complet Portail Membre (Filament) [cite: 8]</span>
                        </li>
                        <li class="flex items-start gap-3">
                           <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Journalisation d'audit complète </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Support prioritaire Sentry</span>
                        </li>
                    </ul>

                    <div class="card-actions">
                        <button class="btn btn-success btn-block text-white">S'abonner maintenant</button>
                    </div>
                </div>
            </div>
            @else
            <div class="card bg-base-100 shadow-xl border border-base-300">
                <div class="card-body gap-6">
                    <div>
                        <h3 class="text-2xl font-bold">Entreprise</h3>
                        <p class="text-base-content/60 text-sm">Volume élevé et sur-mesure </p>
                    </div>

                    <div class="flex items-baseline">
                        <span class="text-4xl font-extrabold">Sur devis</span>
                    </div>

                    <ul class="space-y-4 my-4">
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Analyses illimitées</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Versioning de prompts dédié </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>Contrôle total MySQL & Audit </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i data-lucide="check" class="w-5 h-5 text-success shrink-0"></i>
                            <span>SLA Garanti & Monitoring dédié [cite: 13]</span>
                        </li>
                    </ul>

                    <div class="card-actions">
                        <button class="btn btn-ghost btn-block border-base-300">Contacter le sales</button>
                    </div>
                </div>
            </div>
@endif
@endforeach
        </div>