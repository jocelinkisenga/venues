<x-app-client>
<div class="max-w-5xl mx-auto p-2">

    <h1 class="text-2xl font-bold mb-6 text-black dark:text-white">
        Upgrade your plan
    </h1>

    @if(session('success'))
<x-plan-alert-upgrade>{{ session('success') }}</x-plan-alert>
    @endif
@if (session('status'))
    <x-plan-alert>{{ session('status') }}</x-plan-alert>
@endif

    <div class="grid md:grid-cols-2 gap-6">

        <!-- PRO -->
        <div class="border rounded-xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-black dark:text-white">Pro Plan</h2>
            <p class="text-gray-500 mt-2 text-black dark:text-white">For growing users</p>

            <div class="mt-4 text-2xl font-bold">
                $9 / month
            </div>

            <ul class="mt-4 space-y-2 text-sm text-gray-600">
                <li>✔ 5,000 tokens</li>
                <li>✔ AI analysis</li>
                <li>✔ Basic support</li>
            </ul>

            <form method="POST" action="">
                @csrf
                <input type="hidden" name="plan" value="pro">

                <input type="hidden" name="payment_method" value="pm_card_visa">

                <button class="mt-6 w-full bg-black text-white dark:bg-white dark:text-black py-2 rounded">
                    Upgrade to Pro
                </button>
            </form>
        </div>

        <!-- PREMIUM -->
        <div class="border rounded-xl p-6 shadow-md border-blue-500">
            <h2 class="text-xl font-semibold text-black dark:text-white">Premium Plan</h2>
            <p class="text-gray-500 mt-2 text-black dark:text-white">For power users</p>

            <div class="mt-4 text-2xl font-bold">
                $29 / month
            </div>

            <ul class="mt-4 space-y-2 text-sm text-gray-600">
                <li>✔ 50,000 tokens</li>
                <li>✔ Advanced AI analysis</li>
                <li>✔ Priority support</li>
                <li>✔ API access</li>
            </ul>

            <form method="POST" action="">
                @csrf
                <input type="hidden" name="plan" value="premium">

                <input type="hidden" name="payment_method" value="pm_card_visa">

                <button class="mt-6 w-full bg-blue-600 text-white py-2 rounded">
                    Upgrade to Premium
                </button>
            </form>
        </div>

    </div>

</div>
</x-app-client>