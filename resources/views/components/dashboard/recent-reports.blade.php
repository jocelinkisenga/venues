<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h2
                class="text-xl font-bold text-slate-900 dark:text-white">

                Recent Reports

            </h2>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                Your latest AI-generated reports.

            </p>

        </div>

        <a
            href="/reports"
            class="text-sm font-medium text-slate-700 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">

            View all →

        </a>

    </div>

    {{-- Mobile Cards --}}
    <div class="grid gap-4 lg:hidden">

        @forelse($recentReports as $report)

            <a
                href="{{route("report.show", ["reportId" => $report->id])}}"
                class="block rounded-3xl border border-slate-200 bg-white p-5 transition hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">

                <div class="flex items-start justify-between">

                    <div>

                        <h3
                            class="font-semibold text-slate-900 dark:text-white">

                            {{ $report->title }}

                        </h3>

                        <p
                            class="mt-2 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">

                            {!! $report->summary !!}

                        </p>

                    </div>

                    <div
                        class="ml-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">

                        📊

                    </div>

                </div>

                <div
                    class="mt-4 flex items-center justify-between">

                    <span
                        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">

                        {{ ucfirst($report->type) }}

                    </span>

                    <span
                        class="text-xs text-slate-500 dark:text-slate-400">

                        {{ $report->created_at?->diffForHumans() }}

                    </span>

                </div>

            </a>

        @empty

            <div
                class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center dark:border-slate-700 dark:bg-slate-900">

                <div class="text-5xl">
                    📄
                </div>

                <h3
                    class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">

                    No Reports Yet

                </h3>

                <p
                    class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                    Generate your first AI report to see it here.

                </p>

            </div>

        @endforelse

    </div>

    {{-- Desktop Table --}}
    <div
        class="hidden overflow-hidden rounded-3xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 lg:block">

        <table class="w-full">

            <thead
                class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950">

                <tr>

                    <th
                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                        Report

                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                        Type

                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                        Status

                    </th>

                    <th
                        class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                        Generated

                    </th>

                    <th
                        class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">

                        Action

                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($recentReports as $report)

                    <tr
                        class="border-b border-slate-100 dark:border-slate-800">

                        <td class="px-6 py-5">

                            <div>

                                <p
                                    class="font-semibold text-slate-900 dark:text-white">

                                    {{ $report->title }}

                                </p>

                                <p
                                    class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                                    {{ Str::limit($report->summary, 80) }}

                                </p>

                            </div>

                        </td>

                        <td class="px-6 py-5">

                            <span
                                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium dark:bg-slate-800">

                                {{ ucfirst($report->type) }}

                            </span>

                        </td>

                        <td class="px-6 py-5">

                            <span
                                class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">

                                {{ ucfirst($report->status) }}

                            </span>

                        </td>

                        <td class="px-6 py-5 text-sm text-slate-500">

                            {{ $report->created_at?->format('M d, Y') }}

                        </td>

                        <td
                            class="px-6 py-5 text-right">

                            <a
                                href="{{route("report.show", ["reportId" => $report->id])}}"
                                class="font-medium text-slate-900 hover:underline dark:text-white">

                                Open

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-slate-500">

                            No reports available.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>