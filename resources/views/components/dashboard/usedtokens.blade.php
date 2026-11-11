
{{-- Carte de Consommation des Tokens IA --}}
@php
use App\Services\TokenService;
use App\Enums\FreeTokensPlan;

  // 1. On s'assure d'avoir un entier (0) si le service retourne null
  $tokens_used = TokenService::getTotalUserTokens() ?? 0;
  $plan = TokenService::totalPlanTokens();
  
  // 2. On ajoute ->value pour extraire l'entier de l'Enum
  $totalPlanTokens = $plan->analysis_quota ?? FreeTokensPlan::FREE->value; 
@endphp

<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs transition-all duration-200 hover:shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0 mb-4">
        
        {{-- En-tête avec Statut --}}
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-lg shadow-2xs">
                ⚡
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Used Tokens
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Used volume for this month
                </p>
            </div>
        </div>

        {{-- Compteur Numérique (Police Mono pour l'aspect Data) --}}
        <div class="flex items-baseline space-x-1.5 font-mono text-xs sm:text-right">
            <span class="text-gray-400 dark:text-gray-500">used:</span>
            <strong class="text-base font-bold text-gray-900 dark:text-white">
                {{ number_format($tokens_used) }}
            </strong>
        </div>
    </div>

{{-- Barre de Progression --}}
    @php
        $maxTokensLimit = $totalPlanTokens; 
        
        // 3. On empêche la division par zéro si la limite est de 0
        $usagePercentage = $maxTokensLimit > 0 
            ? min(($tokens_used / $maxTokensLimit) * 100, 100) 
            : 0;
            
        // 4. (Bonus) On utilise max() pour éviter un reste négatif si tokens_used > totalPlanTokens
        $remaining = max($totalPlanTokens - $tokens_used, 0);
    @endphp
    <div class="relative w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden shadow-inner">
        <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-500 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"
             style="width: {{ $usagePercentage }}%"></div>
    </div>

    {{-- Pied de carte analytique --}}
    <div class="flex justify-between items-center mt-2.5 text-[11px] text-gray-400 dark:text-gray-500 font-medium">
        <div class="flex items-center gap-1">
            @if($usagePercentage >= 80)
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-amber-600 dark:text-amber-400 font-semibold">Used all tokens</span>
            @else
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Quota control</span>
                <span class="ml-4">remaining :{{ $remaining}}</span>
            @endif
        </div>
        <span class="font-mono bg-gray-50 dark:bg-gray-800/60 px-2 py-0.5 rounded border border-gray-100 dark:border-gray-800 text-black dark:text-white">
            {{ number_format($usagePercentage, 1) }}% of the offer
        </span>
    </div>
</div>
