<div class="space-y-4">

    {{-- Header --}}
    <div>

        <h2
            class="text-xl font-bold text-slate-900 dark:text-white">

            Activity Overview

        </h2>

        <p
            class="mt-1 text-sm text-slate-500 dark:text-slate-400">

            Your account activity and progress.

        </p>

    </div>

    {{-- Stats Grid --}}
    <div
        class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        {{-- Chats --}}
        <div
            class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex items-center justify-between">

                <div>

                    <p
                        class="text-sm text-slate-500 dark:text-slate-400">

                        AI Chats

                    </p>

                    <h3
                        class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">

                        {{ $totalChats ?? 0 }}

                    </h3>

                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">

                    💬

                </div>

            </div>

        </div>

        {{-- Reports --}}
        <div
            class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex items-center justify-between">

                <div>

                    <p
                        class="text-sm text-slate-500 dark:text-slate-400">

                        Reports

                    </p>

                    <h3
                        class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">

                        {{ $totalReports ?? 0 }}

                    </h3>

                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 dark:bg-emerald-950">

                    📊

                </div>

            </div>

        </div>

        {{-- Documents --}}
        <div
            class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex items-center justify-between">

                <div>

                    <p
                        class="text-sm text-slate-500 dark:text-slate-400">

                        Documents

                    </p>

                    <h3
                        class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">

                        {{ $totalDocuments ?? 0 }}

                    </h3>

                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 dark:bg-blue-950">

                    📄

                </div>

            </div>

        </div>

        {{-- Profile Completion --}}
        <div
            class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">

            <div
                class="flex items-center justify-between">

                <div>

                    <p
                        class="text-sm text-slate-500 dark:text-slate-400">

                        Tax Profile

                    </p>

                    <h3
                        class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">

                        {{ $profileCompletion ?? 0 }}%

                    </h3>

                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-950">

                    👤

                </div>

            </div>

            <div
                class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">

                <div
                    class="h-full rounded-full bg-amber-500"
                    style="width: {{ $profileCompletion ?? 0 }}%">
                </div>

            </div>

        </div>

    </div>

</div>