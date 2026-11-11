{{-- Subscription Usage --}}
<section class="mt-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                Subscription Usage
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Monitor your plan limits and AI consumption.
            </p>
        </div>

        <button
            class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
            Upgrade Plan
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        {{-- AI Chats --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-slate-900 dark:text-white">
                    AI Chats
                </h4>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    Monthly
                </span>
            </div>

            <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                18 / 50
            </p>

            {{-- progress --}}
            <div class="mt-3 h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-2 w-[36%] rounded-full bg-indigo-500"></div>
            </div>

            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                32 chats remaining
            </p>
        </div>

        {{-- Reports --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-slate-900 dark:text-white">
                    AI Reports
                </h4>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    Monthly
                </span>
            </div>

            <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                5 / 10
            </p>

            <div class="mt-3 h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-2 w-[50%] rounded-full bg-emerald-500"></div>
            </div>

            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Half used
            </p>
        </div>

        {{-- Documents --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-slate-900 dark:text-white">
                    Document Analysis
                </h4>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    Monthly
                </span>
            </div>

            <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                7 / 20
            </p>

            <div class="mt-3 h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-2 w-[35%] rounded-full bg-amber-500"></div>
            </div>

            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Safe usage
            </p>
        </div>

    </div>

    {{-- Upgrade Banner --}}
    <div class="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-6 dark:border-indigo-900 dark:bg-indigo-500/10">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h4 class="text-lg font-bold text-indigo-700 dark:text-indigo-300">
                    Need more power?
                </h4>
                <p class="text-sm text-indigo-600 dark:text-indigo-400">
                    Upgrade your plan to unlock unlimited AI reports and advanced tax analysis.
                </p>
            </div>

            <button
                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                Upgrade Now
            </button>

        </div>

    </div>

</section>