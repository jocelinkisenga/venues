<x-app-client>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 px-4 sm:px-6 py-12 transition-colors duration-300">
        <div class="max-w-md w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-8 text-center">

            {{-- Icône de succès --}}
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/20 mb-6">
                <svg class="h-8 w-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            {{-- Titre & Message --}}
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                Thank you for your subscription!
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 leading-relaxed">
                Welcome to the <span class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->plan ?? 'Pro' }}</span> tier. Your ILANDS account is now  ready for tax planning and reporting.
            </p>

            {{-- Séparateur subtil --}}
            <hr class="border-gray-100 dark:border-gray-800/60 mb-8" />

            {{-- Bouton d'action principal --}}
            <a href="{{ route('dashboard') }}"
               class="inline-flex w-full items-center justify-center rounded-xl bg-gray-900 dark:bg-white px-5 py-3.5 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors">
                Go to Dashboard
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>

            {{-- Informations secondaires --}}
{{--             <p class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                A receipt has been sent to <span class="font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->email }}</span>.<br>
                You can manage your billing details at any time in your settings.
            </p> --}}
            
        </div>
    </div>
</x-app-client>