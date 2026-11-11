<x-app-client>
    <div class="min-h-screen bg-slate-50 dark:bg-black text-slate-900 dark:text-white flex flex-col items-center justify-center px-6 py-12 transition-colors duration-300">
        
        <div class="max-w-xl w-full">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-slate-900 dark:text-white">
                    Complete Your Subscription
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                    Choose your plan and enter your payment details below.
                </p>
            </div>

            <form id="payment-form" action="{{ route('subscription.process') }}" method="POST" class="space-y-6">
                @csrf
                
                <input type="hidden" name="payment_method" id="payment-method">
                <input type="hidden" name="plan" id="selected-plan" value="pro">

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button type="button" id="btn-plan-pro" onclick="selectPlan('pro')"
                        class="p-4 rounded-xl border-2 text-left transition-all duration-200 bg-white dark:bg-slate-900/50 border-blue-500 ring-2 ring-blue-500/20">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-sm uppercase tracking-wider">Pro Plan</span>
                            <span class="w-4 h-4 rounded-full bg-blue-500 flex items-center justify-center text-[10px] text-white font-bold" id="badge-pro">✓</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Perfect for individuals starting out.</p>
                    </button>

                    <button type="button" id="btn-plan-premium" onclick="selectPlan('premium')"
                        class="p-4 rounded-xl border-2 text-left transition-all duration-200 bg-white dark:bg-slate-900/50 border-slate-200 dark:border-white/10 opacity-70">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-sm uppercase tracking-wider">Premium Plan</span>
                            <span class="w-4 h-4 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px]" id="badge-premium"></span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Full power for advanced power users.</p>
                    </button>
                </div>

                <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl">
                    
                    <div class="space-y-6">
                        <div>
                            <label for="card-holder-name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Cardholder Name</label>
                            <input type="text" id="card-holder-name" class="w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 pr-12 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-800 outline-none transition" placeholder="your name" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Card Details</label>
                            <div id="card-element" class="w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 pr-12 text-black dark:text-white  focus:ring-4  dark:focus:ring-slate-800 outline-none "></div>
                            <div id="card-errors" class="text-red-500 text-xs mt-2 font-medium" role="alert"></div>
                        </div>

                        <button id="card-button" data-secret="{{ $intent->client_secret }}" type="submit"
                           class="inline-flex items-center justify-center w-full bg-blue-600 hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400 text-white py-4 px-6 rounded-xl font-semibold text-base transition-all duration-200 shadow-lg shadow-blue-600/20 active:scale-[0.98]">
                            <span id="button-text">Subscribe to <span id="submit-btn-plan-text">Pro</span></span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // 1. FONCTION JAVASCRIPT POUR CHANGER DE PLAN DYNAMIQUEMENT
        function selectPlan(planName) {
            // Mettre à jour la valeur du champ caché pour Laravel
            document.getElementById('selected-plan').value = planName;

            // Éléments DOM
            const btnPro = document.getElementById('btn-plan-pro');
            const btnPremium = document.getElementById('btn-plan-premium');
            const badgePro = document.getElementById('badge-pro');
            const badgePremium = document.getElementById('badge-premium');
            const btnText = document.getElementById('submit-btn-plan-text');

            if (planName === 'pro') {
                // Style bouton Pro Actif
                btnPro.className = "p-4 rounded-xl border-2 text-left transition-all duration-200 bg-white dark:bg-slate-900/50 border-blue-500 ring-2 ring-blue-500/20";
                btnPro.classList.remove('opacity-70');
                badgePro.className = "w-4 h-4 rounded-full bg-blue-500 flex items-center justify-center text-[10px] text-white font-bold";
                badgePro.innerText = "✓";

                // Style bouton Premium Inactif
                btnPremium.className = "p-4 rounded-xl border-2 text-left transition-all duration-200 bg-white dark:bg-slate-900/50 border-slate-200 dark:border-white/10 opacity-70";
                badgePremium.className = "w-4 h-4 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px]";
                badgePremium.innerText = "";

                btnText.innerText = "Pro";
            } else {
                // Style bouton Premium Actif
                btnPremium.className = "p-4 rounded-xl border-2 text-left transition-all duration-200 bg-white dark:bg-slate-900/50 border-blue-500 ring-2 ring-blue-500/20";
                btnPremium.classList.remove('opacity-70');
                badgePremium.className = "w-4 h-4 rounded-full bg-blue-500 flex items-center justify-center text-[10px] text-white font-bold";
                badgePremium.innerText = "✓";

                // Style bouton Pro Inactif
                btnPro.className = "p-4 rounded-xl border-2 text-left transition-all duration-200 bg-white dark:bg-slate-900/50 border-slate-200 dark:border-white/10 opacity-70";
                badgePro.className = "w-4 h-4 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px]";
                badgePro.innerText = "";

                btnText.innerText = "Premium";
            }
        }

        // 2. INITIALISATION STRIPE
        document.addEventListener('DOMContentLoaded', function () {
            const stripeKey = "{{ config('services.stripe.key') }}"; 
            if (!stripeKey) return;

            const stripe = Stripe(stripeKey);
            const elements = stripe.elements();

            const isDark = document.documentElement.classList.contains('dark');
            const style = {
                base: {
                    color : isDark ? '#ffffff' : '#0f172a',
                    fontFamily : 'inter, system-ui, sans-serif',
                    fontSize : '16px',
                    '::placeholde':{
                        color: isDark ? '#94a3b8' :'#64748b',
                    }
                },
                invalid:{
                    color:'#dc2626',
                    iconColor:"#dc2626"
                }
            }
            
{{--             const style = {
                base: {
                    color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#ffffff' : 'black',
                    fontFamily: 'ui-sans-serif, system-ui, sans-serif',
                    fontSize: '14px',
                    '::placeholder': { color: '#64748b' }
                }
            }; --}}

            const cardElement = elements.create('card', { style: style, hidePostalCode: true });
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;
            const cardHolderName = document.getElementById('card-holder-name');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                cardButton.disabled = true;
                document.getElementById('button-text').innerText = 'Processing...';

                const { setupIntent, error } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: { name: cardHolderName.value }
                        }
                    }
                );

                if (error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    cardButton.disabled = false;
                    document.getElementById('button-text').innerHTML = `Subscribe to <span>${document.getElementById('submit-btn-plan-text').innerText}</span>`;
                } else {
                    document.getElementById('payment-method').value = setupIntent.payment_method;
                    form.submit();
                }
            });
        });
    </script>
</x-app-client>