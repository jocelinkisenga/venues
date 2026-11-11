<div>
    <div class="space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            AI Configuration
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Configure the primary AI model and automatic failover strategy.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700 dark:border-blue-900/40 dark:bg-blue-950/30 dark:text-blue-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Primary model
            </h2>

            <p class="text-sm text-gray-500">
                This model receives requests first.
            </p>
        </div>

        <select
            wire:model="primaryModelId"
            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-950"
        >
            <option value="">
                Select a primary model
            </option>

            @foreach ($models as $model)
                <option value="{{ $model->id }}">
                    {{ $model->provider->name }}
                    — {{ $model->label ?: $model->model_identifier }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Automatic failover
                </h2>

                <p class="text-sm text-gray-500">
                    Switch automatically when a provider temporarily fails.
                </p>
            </div>

            <input
                type="checkbox"
                wire:model="automaticFailover"
                class="toggle"
            >
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium">
                Enable fallback models
            </label>

            <input
                type="checkbox"
                wire:model="fallbackEnabled"
                class="toggle mt-2"
            >
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Fallback order
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Models are attempted in this order after the primary model.
        </p>

        <div class="mt-4 space-y-3">

            @foreach ($models as $model)

                @if ($model->id != $primaryModelId)

                    <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-4 dark:border-gray-800">

                        <input
                            type="checkbox"
                            value="{{ $model->id }}"
                            wire:model="fallbackModelIds"
                            class="checkbox"
                        >

                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $model->provider->name }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ $model->label ?: $model->model_identifier }}
                            </div>
                        </div>

                    </label>

                @endif

            @endforeach

        </div>
    </div>

    <div class="flex justify-end">
        <button
            wire:click="save"
            wire:loading.attr="disabled"
            class="rounded-xl bg-blue-600 px-5 py-3 font-medium text-white transition hover:bg-blue-700 disabled:opacity-50"
        >
            <span wire:loading.remove>
                Save configuration
            </span>

            <span wire:loading>
                Saving...
            </span>
        </button>
    </div>

  </div>
</div>
