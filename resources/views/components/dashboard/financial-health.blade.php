{{-- Financial Health Score --}}
<section class="mt-10">

    {{-- Header --}}
    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                Financial Health Score
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                AI-powered overview of your financial stability and tax position.
            </p>
        </div>

        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
            ● AI Estimated
        </span>
    </div>

    {{-- Main Card --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

        {{-- SCORE GAUGE --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900 md:col-span-1">

            <div class="flex flex-col items-center justify-center text-center">

                {{-- Circle Score --}}
                <div class="relative flex items-center justify-center w-40 h-40">

                    <svg class="absolute" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" stroke="currentColor"
                            class="text-slate-200 dark:text-slate-800" stroke-width="10" fill="none" />
                        <circle cx="60" cy="60" r="54" stroke="currentColor"
                            class="text-emerald-500" stroke-width="10" fill="none"
                            stroke-dasharray="339"
                            stroke-dashoffset="85"
                            stroke-linecap="round" />
                    </svg>

                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">
                            78
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            / 100
                        </p>
                    </div>

                </div>

                <h4 class="mt-4 text-lg font-bold text-emerald-600 dark:text-emerald-400">
                    Good Financial Health
                </h4>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Your profile shows stable income with moderate tax optimization potential.
                </p>

            </div>

        </div>

        {{-- BREAKDOWN --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900 md:col-span-2">

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">
                Breakdown
            </h4>

            <div class="space-y-4">

                {{-- Income Stability --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-500 dark:text-slate-400">Income Stability</span>
                        <span class="font-semibold text-slate-900 dark:text-white">82%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-2 w-[82%] rounded-full bg-indigo-500"></div>
                    </div>
                </div>

                {{-- Tax Optimization --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-500 dark:text-slate-400">Tax Optimization</span>
                        <span class="font-semibold text-slate-900 dark:text-white">61%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-2 w-[61%] rounded-full bg-amber-500"></div>
                    </div>
                </div>

                {{-- Risk Level --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-500 dark:text-slate-400">Risk Exposure</span>
                        <span class="font-semibold text-slate-900 dark:text-white">24%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-2 w-[24%] rounded-full bg-emerald-500"></div>
                    </div>
                </div>

                {{-- Complexity --}}
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-500 dark:text-slate-400">Financial Complexity</span>
                        <span class="font-semibold text-slate-900 dark:text-white">47%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-2 w-[47%] rounded-full bg-slate-400"></div>
                    </div>
                </div>

            </div>

            {{-- Insight Box --}}
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">

                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                    AI Insight
                </p>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    You are eligible for additional deductions optimization based on your income structure.
                    Consider reviewing Schedule C and retirement contributions.
                </p>

            </div>

        </div>

    </div>

</section>