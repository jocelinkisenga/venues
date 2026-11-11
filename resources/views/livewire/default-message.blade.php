<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                Default questions
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage the defaults ai questions available to ILANDS.
            </p>
        </div>

        <button
            type="button"
            wire:click="create"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
        >
            + Add Question
        </button>

    </div>


    {{-- Flash messages --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif


    {{-- Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <input
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Search providers..."
            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
        >

    </div>


    {{-- Providers --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">

                <thead class="bg-gray-50 dark:bg-gray-950/50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Question
                        </th>


                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">

                    @forelse ($questions as $question)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-950/40">

                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $question->message }}
                                </div>

                            </td>


                            <td class="px-6 py-4">

                                @if ($question->is_anabled == true)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Enabled
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Disabled
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <button
                                        wire:click="edit({{ $question->id }})"
                                        class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        wire:click="toggleStatus({{ $question->id }})"
                                        class="rounded-lg px-3 py-2 text-sm font-medium {{ $question->is_enabled ? 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/30' : 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30' }}"
                                    >
                                        {{ $question->is_enabled ? 'Disable' : 'Enable' }}
                                    </button>

                                    <button
                                        wire:click="confirmDelete({{ $question->id }})"
                                        class="rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30"
                                    >
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    No questions found.
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    Add your first AI question to get started.
                                </div>
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            {{-- {{ $question->links() }} --}}
        </div>

    </div>


    {{-- Create / Edit modal --}}
    @if ($showForm)

        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        >

            <div
                class="w-full max-w-lg rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900"
            >

                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                    <div>
                        <h2 class="font-semibold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Edit your question' : 'Add a question' }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            add a question
                        </p>
                    </div>

                    <button
                        wire:click="cancelForm"
                        class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
                    >
                        ✕
                    </button>

                </div>


                <form wire:submit="save" class="space-y-5 p-6">

                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            your question
                        </label>

                        <input
                            type="text"
                            wire:model="message"
                            placeholder="Google Gemini"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                        >

                        @error('message')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                


                    <label class="flex items-center gap-3">

                        <input
                            type="checkbox"
                            wire:model="isEnabled"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                        >

                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            question enabled
                        </span>

                    </label>


                    <div class="flex justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-800">

                        <button
                            type="button"
                            wire:click="cancelForm"
                            class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
                        >
                            Save question
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- Delete confirmation --}}
    @if ($deletingId)

        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-800 dark:bg-gray-900">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Delete question?
                </h2>

              

                <div class="mt-6 flex justify-end gap-3">

                    <button
                        wire:click="cancelDelete"
                        class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Cancel
                    </button>

                    <button
                        wire:click="delete"
                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
                    >
                        Delete
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>