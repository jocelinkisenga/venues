<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 py-12 bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

        <div class="max-w-md w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-8 text-center">

            {{-- Icône Enveloppe --}}
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>

            {{-- Titre et texte principal --}}
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                Check your email
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 leading-relaxed">
                We've sent a verification link to the email address you used during registration. Please click the link to activate your account.
            </p>

            {{-- Message de succès (Alerte subtile) --}}
            @if (session('status') === 'verification-link-sent')
                <div class="mb-6 flex items-start gap-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 p-4 text-left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                        A new verification link has been sent to your email address.
                    </p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gray-900 dark:bg-white px-5 py-3 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700">
                        Log Out
                    </button>
                </form>
            </div>

            {{-- Note de bas de page --}}
            <p class="mt-8 text-xs text-gray-400 dark:text-gray-500">
                Having trouble receiving the email? Check your Spam folder or wait a few minutes before requesting another link.
            </p>

        </div>

    </div>
</x-guest-layout>