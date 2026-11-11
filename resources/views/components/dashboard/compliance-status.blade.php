{{-- Compliance Status --}}
<section class="mt-10">

    {{-- Header --}}
    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between mb-6">

        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                Compliance Status
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                IRS readiness and regulatory compliance overview.
            </p>
        </div>

        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
            ● Verified Status
        </span>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

        {{-- Global Score --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900 md:col-span-1">

            <div class="text-center">

                {{-- Circle --}}
                <div class="relative mx-auto flex items-center justify-center w-40 h-40">

                    <svg class="absolute" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54"
                            class="text-slate-200 dark:text-slate-800"
                            stroke="currentColor"
                            stroke-width="10"
                            fill="none" />

                        <circle cx="60" cy="60" r="54"
                            class="text-indigo-500"
                            stroke="currentColor"
                            stroke-width="10"
                            fill="none"
                            stroke-dasharray="339"
                            stroke-dashoffset="55"
                            stroke-linecap="round" />
                    </svg>

                    <div>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">84</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Compliance Score</p>
                    </div>

                </div>

                <h4 class="mt-4 text-lg font-bold text-indigo-600 dark:text-indigo-400">
                    Mostly Compliant
                </h4>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Your profile is well aligned with IRS requirements, minor gaps detected.
                </p>

            </div>

        </div>

        {{-- Checklist --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900 md:col-span-2">

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">
                Compliance Checklist
            </h4>

            <div class="space-y-4">

                {{-- Item 1 --}}
                <div class="flex items-center justify-between rounded-xl border border-slate-100 dark:border-slate-800 p-3">

                    <div class="flex items-center gap-3">
                        <span class="text-green-500 text-lg">✔</span>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">
                                Identity Verification
                            </p>
                            <p class="text-xs text-slate-500">SSN / EIN validated</p>
                        </div>
                    </div>

                    <span class="text-xs font-semibold text-green-600 dark:text-green-400">
                        Completed
                    </span>

                </div>

                {{-- Item 2 --}}
                <div class="flex items-center justify-between rounded-xl border border-slate-100 dark:border-slate-800 p-3">

                    <div class="flex items-center gap-3">
                        <span class="text-amber-500 text-lg">⚠</span>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">
                                Tax Profile Completion
                            </p>
                            <p class="text-xs text-slate-500">Missing income sources</p>
                        </div>
                    </div>

                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">
                        Partial
                    </span>

                </div>

                {{-- Item 3 --}}
                <div class="flex items-center justify-between rounded-xl border border-slate-100 dark:border-slate-800 p-3">

                    <div class="flex items-center gap-3">
                        <span class="text-green-500 text-lg">✔</span>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">
                                Document Uploads
                            </p>
                            <p class="text-xs text-slate-500">W-2 / 1099 / receipts</p>
                        </div>
                    </div>

                    <span class="text-xs font-semibold text-green-600 dark:text-green-400">
                        Completed
                    </span>

                </div>

                {{-- Item 4 --}}
                <div class="flex items-center justify-between rounded-xl border border-slate-100 dark:border-slate-800 p-3">

                    <div class="flex items-center gap-3">
                        <span class="text-red-500 text-lg">✕</span>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">
                                State Tax Configuration
                            </p>
                            <p class="text-xs text-slate-500">Not configured</p>
                        </div>
                    </div>

                    <span class="text-xs font-semibold text-red-600 dark:text-red-400">
                        Missing
                    </span>

                </div>

            </div>

            {{-- Warning Box --}}
            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/30 dark:bg-amber-500/10">

                <p class="text-sm font-semibold text-amber-700 dark:text-amber-300">
                    Action Recommended
                </p>

                <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                    Completing your tax profile will improve compliance accuracy and reduce audit risk.
                </p>

            </div>

        </div>

    </div>

</section>