<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                AI Models
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage the models available for AI generation and failover.
            </p>
        </div>

        <button
            type="button"
            wire:click="create"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        >
            + Add Model
        </button>
    </div>

    {{-- Messages --}}
    @if (session('success'))
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="grid gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-2 dark:border-gray-800 dark:bg-gray-900">
        <input
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Search models..."
            class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
        >

        <select
            wire:model.live="providerId"
            class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
        >
            <option value="">All providers</option>
            @foreach ($providers as $provider)
                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Model</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Provider</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($models as $model)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-950/40">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $model->label ?: $model->name }}
                                </div>
                                <code class="mt-1 block text-xs text-gray-500">
                                    {{ $model->model_identifier }}
                                </code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $model->provider->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $model->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($model->is_enabled)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-950/40 dark:text-blue-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
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
                                <div class="flex justify-end gap-2">
                                    <button
                                        wire:click="edit({{ $model->id }})"
                                        class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:ring-gray-700"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        wire:click="toggleStatus({{ $model->id }})"
                                        class="rounded-lg px-3 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-gray-900 {{ $model->is_enabled ? 'text-amber-600 hover:bg-amber-50 focus:ring-amber-500' : 'text-blue-600 hover:bg-blue-50 focus:ring-blue-500' }}"
                                    >
                                        {{ $model->is_enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                    <button
                                        wire:click="confirmDelete({{ $model->id }})"
                                        class="rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 dark:focus:ring-offset-gray-900"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                No AI models found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            {{ $models->links() }}
        </div>
    </div>

    {{-- Form Modal --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 sm:p-6 backdrop-blur-sm transition-opacity">
            
            {{-- Modal Container (Responsive & internal scroll) --}}
            <div class="flex max-h-full w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900">
                
                {{-- Modal Header (Fixed) --}}
                <div class="flex flex-none items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <div>
                        <h2 class="font-semibold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Edit AI Model' : 'Add AI Model' }}
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Configure the model identifier used by Prism.
                        </p>
                    </div>
                    <button
                        wire:click="cancelForm"
                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                    >
                        ✕
                    </button>
                </div>

                <form wire:submit="save" class="flex flex-1 flex-col overflow-hidden">
                    
                    {{-- Modal Body (Scrollable) --}}
                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        
                        {{-- Provider --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                Provider
                            </label>
                            <select
                                wire:model="providerId"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                            >
                                <option value="">Select provider</option>
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                            @error('providerId')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Name + label --}}
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Internal name
                                </label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    placeholder="Gemini Flash"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                                >
                                @error('name')
                                    <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Display label
                                </label>
                                <input
                                    type="text"
                                    wire:model="label"
                                    placeholder="Gemini Flash Latest"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                                >
                            </div>
                        </div>

                        {{-- Identifier --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                Model identifier
                            </label>
                            <input
                                type="text"
                                wire:model="modelIdentifier"
                                placeholder="gemini-flash-latest"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 font-mono text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                            >
                            <p class="mt-1.5 text-xs text-gray-500">
                                Exact model identifier passed to Prism.
                            </p>
                            @error('modelIdentifier')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                Priority
                            </label>
                            <input
                                type="number"
                                wire:model="priority"
                                min="1"
                                max="10000"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                            >
                            <p class="mt-1.5 text-xs text-gray-500">
                                Lower numbers represent higher priority.
                            </p>
                        </div>

                        {{-- Token limits --}}
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Max input tokens
                                </label>
                                <input
                                    type="number"
                                    wire:model="maxInputTokens"
                                    min="1"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Max output tokens
                                </label>
                                <input
                                    type="number"
                                    wire:model="maxOutputTokens"
                                    min="1"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                                >
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Input price / 1M tokens
                                </label>
                                <input
                                    type="number"
                                    step="0.000001"
                                    wire:model="inputPricePerMillion"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Output price / 1M tokens
                                </label>
                                <input
                                    type="number"
                                    step="0.000001"
                                    wire:model="outputPricePerMillion"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20"
                                >
                            </div>
                        </div>

                        {{-- Enabled --}}
                        <label class="flex w-fit cursor-pointer items-center gap-3">
                            <input
                                type="checkbox"
                                wire:model="isEnabled"
                                class="h-5 w-5 rounded border-gray-300 text-blue-600 transition focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:checked:bg-blue-600"
                            >
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                Model enabled
                            </span>
                        </label>

                    </div>

                    {{-- Modal Footer (Fixed) --}}
                    <div class="flex flex-none justify-end gap-3 border-t border-gray-200 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-gray-950/50">
                        <button
                            type="button"
                            wire:click="cancelForm"
                            class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:ring-gray-700"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-900"
                        >
                            <span wire:loading.remove>
                                Save Model
                            </span>
                            <span wire:loading>
                                Saving...
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- Delete confirmation --}}
    @if ($deletingId)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4 sm:p-6 backdrop-blur-sm transition-opacity">
            <div class="w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Delete AI model?
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                        The model cannot be deleted while it is configured as the primary or fallback model.
                    </p>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-gray-950/50">
                    <button
                        wire:click="cancelDelete"
                        class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:ring-gray-700"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="delete"
                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>