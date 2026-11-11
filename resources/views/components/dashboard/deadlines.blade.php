{{-- Upcoming Deadlines --}}
<section class="mt-10">

    {{-- Header --}}
    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between mb-6">

        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                Upcoming Deadlines
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Stay compliant with IRS and business tax deadlines.
            </p>
        </div>

        <button class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
            View Calendar
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </button>

    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

        {{-- Deadline 1 --}}
        <div class="rounded-2xl border border-red-200 bg-white p-6 dark:border-red-900/40 dark:bg-slate-900">

            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-red-500">
                    Critical
                </span>

                <span class="text-xs text-slate-500 dark:text-slate-400">
                    IRS
                </span>
            </div>

            <h4 class="mt-3 text-lg font-bold text-slate-900 dark:text-white">
                Form 1040 Submission
            </h4>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Individual income tax return filing deadline.
            </p>

            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                    April 15, 2026
                </span>

                <span class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-600 dark:bg-red-500/10">
                    3 days left
                </span>
            </div>

        </div>

        {{-- Deadline 2 --}}
        <div class="rounded-2xl border border-amber-200 bg-white p-6 dark:border-amber-900/40 dark:bg-slate-900">

            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-500">
                    Soon
                </span>

                <span class="text-xs text-slate-500 dark:text-slate-400">
                    Business
                </span>
            </div>

            <h4 class="mt-3 text-lg font-bold text-slate-900 dark:text-white">
                Quarterly Estimated Tax
            </h4>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Q2 estimated payments for self-employed users.
            </p>

            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">
                    June 15, 2026
                </span>

                <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600 dark:bg-amber-500/10">
                    18 days left
                </span>
            </div>

        </div>

        {{-- Deadline 3 --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Upcoming
                </span>

                <span class="text-xs text-slate-500 dark:text-slate-400">
                    Compliance
                </span>
            </div>

            <h4 class="mt-3 text-lg font-bold text-slate-900 dark:text-white">
                Business License Renewal
            </h4>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Annual renewal requirement for registered entities.
            </p>

            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    July 01, 2026
                </span>

                <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    34 days left
                </span>
            </div>

        </div>

    </div>

    {{-- Alert Banner --}}
    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-900/30 dark:bg-red-500/10">

        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">

            <div>
                <h4 class="text-sm font-bold text-red-700 dark:text-red-300">
                    Action Required
                </h4>
                <p class="text-xs text-red-600 dark:text-red-400">
                    You have at least one critical tax deadline approaching. Review your reports immediately.
                </p>
            </div>

            <button class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">
                Review Now
            </button>

        </div>

    </div>

</section>