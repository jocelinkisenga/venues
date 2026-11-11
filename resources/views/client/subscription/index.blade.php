@php
use App\Services\TokenService;
use App\Enums\FreeTokensPlan;

  // 1. On s'assure d'avoir un entier (0) si le service retourne null
  $tokens_used = TokenService::getTotalUserTokens() ?? 0;
  $plan = TokenService::totalPlanTokens();
  
  // 2. On ajoute ->value pour extraire l'entier de l'Enum
  $totalPlanTokens = $plan->analysis_quota ?? FreeTokensPlan::FREE->value; 
@endphp
    @php
        $maxTokensLimit = $totalPlanTokens; 
        
        // 3. On empêche la division par zéro si la limite est de 0
        $usagePercentage = $maxTokensLimit > 0 
            ? min(($tokens_used / $maxTokensLimit) * 100, 100) 
            : 0;
            
        // 4. (Bonus) On utilise max() pour éviter un reste négatif si tokens_used > totalPlanTokens
        $remaining = max($totalPlanTokens - $tokens_used, 0);
    @endphp
<x-app-client>

    <div class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen py-10 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <header class="border-b border-gray-200 dark:border-gray-800 pb-6">
                <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Manage my subscrition</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>
            </header>

            @if($subscription && $subscription->valid())
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    <div class="lg:col-span-2 space-y-8">
                        
                        <section class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs p-6 relative overflow-hidden" aria-labelledby="billing-summary-title">
                            <div class="absolute top-0 right-0 p-4">
                                @if($subscription->onTrial())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Trail</span>
                                @elseif($subscription->canceled())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Canceled</span>
                                @elseif($user->subscription('default')->pastDue())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800">Past Due</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Active</span>
                                @endif
                            </div>

                            <h2 id="billing-summary-title" class="text-lg font-bold tracking-tight mb-4">Offer details</h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Plan</p>
                                    <p class="font-medium text-base mt-0.5">{{ ucfirst($subscription->type) }} : {{auth()->user()->plan}}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Client Id</p>
                                    <p class="font-mono text-xs mt-1 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded inline-block text-gray-700 dark:text-gray-300">{{ $user->stripe_id }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Start date</p>
                                    <p class="font-medium mt-0.5">{{ $subscription->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ $subscription->canceled() ? 'Date d\'expiration' : 'Date de renouvellement' }}
                                    </p>
                                    <p class="font-medium mt-0.5 text-gray-900 dark:text-gray-100">
                                        {{ $nextPaymentDate ?? 'N/A' }}
                                    </p>
                                </div>
                                @if($user->pm_last_four)
                                <div class="sm:col-span-2 border-t border-gray-100 dark:border-gray-800 pt-4 mt-2 flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-md">
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Payement method</p>
                                        <p class="text-sm font-medium">•••• •••• •••• {{ $user->pm_last_four }} ({{ strtoupper($user->pm_type ?? 'Carte') }})</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </section>

                        <section class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs p-6" aria-labelledby="billing-cycle-title">
                            <div class="flex justify-between items-baseline mb-2">
                                <h3 id="billing-cycle-title" class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Payment cycle</h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $daysUsed }} / {{ $totalDays }} used dates</span>
                            </div>
                            
                            <div class="w-full bg-gray-100 dark:bg-gray-800 h-2.5 rounded-full overflow-hidden" role="progressbar" aria-valuenow="{{ $cycleProgress }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: {{ $cycleProgress }}%"></div>
                            </div>
                            
                            <div class="mt-4 flex flex-wrap justify-between items-center text-sm gap-2">
                                <p class="text-gray-600 dark:text-gray-400">You have <span class="font-semibold text-gray-900 dark:text-white">{{ $daysRemaining }} days</span> until next payement.</p>
                                <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded font-medium">Next subsciption {{ $nextPaymentDate }}</span>
                            </div>
                        </section>

                    </div>

                    <div>
                        <section class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs p-6 h-full flex flex-col justify-between" aria-labelledby="account-state-title">
                            <div>
                                <h2 id="account-state-title" class="text-lg font-bold tracking-tight mb-4">Statut of the workspace</h2>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2.5 text-sm text-green-600 dark:text-green-400 font-medium">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                        </svg>
                                        Active subscription
                                    </div>

                                    {{-- <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                                        <div class="flex justify-between text-xs font-medium mb-1.5">
                                            <span class="text-gray-500 dark:text-gray-400">tokens used</span>
                                            <span class="text-gray-900 dark:text-gray-100">{{ number_format($tokens_used) }} / {{ $totalPlanTokens }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $usageMetrics['percentage'] }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Quota : {{ $usageMetrics['total'] - $usageMetrics['used'] }} ressources</p>
                                    </div> --}}
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

                                    {{-- end --}}
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400">
                                An question ? contact us on support@unstackmytaxes.com
                            </div>
                        </section>
                    </div>
                </div>

                <section aria-labelledby="quick-actions-title">
                    <h2 id="quick-actions-title" class="sr-only">Billing actions</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <a href="{{ route('subscription.billing') }}" class="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5 rounded-xl shadow-xs transition-all duration-200 hover:scale-[1.02] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 flex items-center gap-4" aria-label="Manage global params of your payments">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:bg-indigo-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.128l1.41-.513M5.106 17.785l1.15-.827m11.379-8.16l1.15-.827M8.14 21.27l.707-1.03m7.74-.808l.707-1.03M12 3v1.5m0 15V21m4.743-10l-1.149-.827" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm">Manage my plan</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"></p>
                            </div>
                        </a>

                        <a href="{{ route('subscription.billing') }}" class="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5 rounded-xl shadow-xs transition-all duration-200 hover:scale-[1.02] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 flex items-center gap-4" aria-label="Mettre à jour la carte bancaire sur Stripe">
                            <div class="p-3 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg group-hover:bg-blue-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm">Update my card</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Change my billing method</p>
                            </div>
                        </a>

                        <a href="#invoices-section" class="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5 rounded-xl shadow-xs transition-all duration-200 hover:scale-[1.02] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 flex items-center gap-4" aria-label="Faire défiler jusqu'à l'historique des factures">
                            <div class="p-3 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg group-hover:bg-blue-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm">see my invoices</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Transaction hystory</p>
                            </div>
                        </a>

                        <a href="{{ route('subscription.billing') }}" class="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5 rounded-xl shadow-xs transition-all duration-200 hover:scale-[1.02] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 flex items-center gap-4" aria-label="Résilier votre forfait actuel sur Stripe">
                            <div class="p-3 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 rounded-lg group-hover:bg-red-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 9m-4.772 0L9 9m12 4.5d1.5 0 0 0-1.5-1.5H18.25m-16.5 0A1.5 1.5 0 0 0 3 13.5H5.75m12.75 0V4.5a2.25 2.25 0 0 0-2.25-2.25h-9a2.25 2.25 0 0 0-2.25 2.25V13.5m14.25 0h-14.25M16.5 21.75H7.5A2.25 2.25 0 0 1 5.25 19.5V13.5h13.5v6a2.25 2.25 0 0 1-2.25 2.25Z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm">cancel my plan</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"></p>
                            </div>
                        </a>
                    </div>
                </section>

                <section id="invoices-section" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden" aria-labelledby="invoices-title">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                        <h2 id="invoices-title" class="text-lg font-bold tracking-tight">track my invoices</h2>
                    </div>
                    
                    @if(count($invoices) > 0)
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-950/50 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800">
                                        <th scope="col" class="px-6 py-3.5 font-semibold">Date </th>
                                        <th scope="col" class="px-6 py-3.5 font-semibold">Price</th>
                                        <th scope="col" class="px-6 py-3.5 font-semibold">Status</th>
                                        <th scope="col" class="px-6 py-3.5 font-semibold text-right">invoice PDF</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                                    @foreach($invoices as $invoice)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4 font-medium">{{ $invoice->date()->format('d M Y') }}</td>
                                            <td class="px-6 py-4 font-mono font-medium">{{ $invoice->total() }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                                    paid
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus-visible:underline" aria-label="Télécharger la facture du {{ $invoice->date()->format('d M Y') }} au format PDF">
                                                    Download
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($invoices as $invoice)
                                <div class="p-4 flex flex-col gap-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->date()->format('d M Y') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-100 dark:border-green-900/30">Payée</span>
                                    </div>

                                 <div class="flex justify-between items-baseline">
                                        <span class="text-gray-500 dark:text-gray-400">Total paid :</span>
                                        <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $invoice->total() }}</span>
                                    </div>
                                    <a href="{{ route('subscription.invoice', $invoice->id) }}" target="_blank" class="mt-1 w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg font-medium transition-colors text-xs" aria-label="Télécharger la facture du {{ $invoice->date()->format('d M Y') }} au format PDF">
                                        download  PDF
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            No invoice found.
                        </div>
                    @endif
                </section>

            @else
                
                <section class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs p-8 md:p-16 text-center max-w-3xl mx-auto" aria-labelledby="no-subscription-title">
                    <div class="mx-auto w-16 h-16 bg-indigo-50 dark:bg-indigo-950/50 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 border border-indigo-100 dark:border-indigo-900/40">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21l8.982-11.795H13.19l1.19-6.105H5.807l3.813 11.804Zm0 0H13.19"/>
                        </svg>
                    </div>
                    
                    <h2 id="no-subscription-title" class="text-xl md:text-2xl font-bold tracking-tight">No active subscription</h2>
                    <p class="mt-2 text-sm md:text-base text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        .
                    </p>
                    
                    <div class="mt-8 flex justify-center">
                        <a href="{{route('pricing')}}" class="inline-flex items-center text-gray-500 border-green-100 justify-center px-5 py-3 rounded-xl font-semibold text-sm bg-black-600 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 text-white font-bold rounded-2xl shadow-lg shadow-blue-950/10 dark:shadow-blue-950/40 transition-all duration-200 ease-out hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 active:scale-[0.98]">
                            Choose a plan
                        </a>
                    </div>
                </section>

            @endif

        </div>
    </div>
</x-app-client>