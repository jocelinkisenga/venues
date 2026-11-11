<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h2
                class="text-xl font-bold text-slate-900 dark:text-white">

                Quick Actions

            </h2>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                Start a task instantly.

            </p>

        </div>

    </div>

    {{-- Actions Grid --}}
    <div
        class="grid grid-cols-2 gap-4 lg:grid-cols-3">

        {{-- New Chat --}}
        <a
            href="{{ route('chat') }}"
            class="group rounded-3xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">

                <svg
                    class="h-6 w-6 text-slate-700 dark:text-slate-300"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 10h8M8 14h5m-8 7l2.5-2.5A2 2 0 014 17h12a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2" />
                </svg>

            </div>

            <h3
                class="mt-4 font-semibold text-slate-900 dark:text-white">

                New AI Chat

            </h3>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                Ask questions about taxes, finance and business.

            </p>

        </a>

        {{-- Upload Document --}}
        <a
            href="{{ route('chat') }}"
            class="group rounded-3xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 dark:bg-blue-950">

                <svg
                    class="h-6 w-6 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 010-8 5 5 0 019.58-1.58A4.5 4.5 0 1117 16H7z" />
                </svg>

            </div>

            <h3
                class="mt-4 font-semibold text-slate-900 dark:text-white">

                Analyze Document

            </h3>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                Upload PDF, DOCX or images for AI review.

            </p>

        </a>

        {{-- Generate Report --}}
        <a
            href="/reports"
            class="group rounded-3xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 dark:bg-emerald-950">

                <svg
                    class="h-6 w-6 text-emerald-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 17v-6m4 6V7m4 10v-3M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                </svg>

            </div>

            <h3
                class="mt-4 font-semibold text-slate-900 dark:text-white">

                Generate Report

            </h3>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                Create professional tax and financial reports.

            </p>

        </a>

        {{-- Tax Profile --}}
{{--         <a
            href=""
            class="group rounded-3xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-950">

                <svg
                    class="h-6 w-6 text-amber-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.54 0 4.92.676 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>

            </div>

            <h3
                class="mt-4 font-semibold text-slate-900 dark:text-white">

                Tax Profile

            </h3>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                Complete your tax profile for better insights.

            </p>

        </a> --}}

    </div>

</div>