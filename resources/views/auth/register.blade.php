<x-auth-layout>

    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
            Create your account
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Start your journey with ILANDS AI workspace
        </p>

    </div>

    <!-- Google Signup -->
    <a href="{{ route('social.redirect', 'google') }}"
       class="flex items-center justify-center gap-3 w-full rounded-2xl border border-slate-200 dark:border-slate-800 py-3 text-sm hover:bg-slate-100 dark:hover:bg-slate-900 transition">

        <svg width="18" height="18" viewBox="0 0 20 20">
             <path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4" />
        <path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853" />
        <path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05" />
        <path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z" fill="#EB4335" />
        </svg>

        Continue with Google
    </a>

    <!-- Divider -->
    <div class="my-6 flex items-center gap-4">
        <div class="h-px bg-slate-200 dark:bg-slate-800 flex-1"></div>
        <span class="text-xs text-slate-400">OR</span>
        <div class="h-px bg-slate-200 dark:bg-slate-800 flex-1"></div>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label class="text-sm text-slate-600 dark:text-slate-400">Full name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="John Doe"
                class="mt-1 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-sm focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-700 outline-none"
                required
            />
            @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="text-sm text-slate-600 dark:text-slate-400">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                class="mt-1 w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-sm focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-700 outline-none"
                required
            />
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
                    <div x-data="{ show: false }" class="relative">

    <input
        :type="show ? 'text' : 'password'"
        name="password"
        placeholder="Password"
        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 pr-12 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-800 outline-none transition"
    />

    <!-- Icon button -->
    <button
        type="button"
        @click="show = !show"
        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">

        <!-- Eye open -->
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>

        <!-- Eye closed -->
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19
                     c-4.478 0-8.268-2.943-9.542-7
                     a9.97 9.97 0 012.293-3.95M6.228 6.228A9.956 9.956 0 0112 5
                     c4.478 0 8.268 2.943 9.542 7
                     a9.971 9.971 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3l18 18" />
        </svg>

    </button>
            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
</div>



        <!-- Confirm Password -->
        <div x-data="{ show: false }" class="relative">

    <input
        :type="show ? 'text' : 'password'"
        name="password_confirmation"
        placeholder="confirm Password"
        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 pr-12 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-800 outline-none transition"
    />

    <!-- Icon button -->
    <button
        type="button"
        @click="show = !show"
        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">

        <!-- Eye open -->
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>

        <!-- Eye closed -->
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19
                     c-4.478 0-8.268-2.943-9.542-7
                     a9.97 9.97 0 012.293-3.95M6.228 6.228A9.956 9.956 0 0112 5
                     c4.478 0 8.268 2.943 9.542 7
                     a9.971 9.971 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3l18 18" />
        </svg>

    </button>

</div>

        <!-- Terms -->
        <div class="flex items-start gap-2 text-sm text-slate-500">
            <input type="checkbox" required class="mt-1 rounded border-slate-300">
            <span>
                I agree to the
                <a href="/terms" class="text-slate-900 dark:text-white font-medium">Terms</a>
                and
                <a href="/privacy" class="text-slate-900 dark:text-white font-medium">Privacy Policy</a>
            </span>
        </div>

        <!-- Button -->
        <button
            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 via-blue-600 to-blue-700 px-6 py-4 font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-500/40 focus:outline-none focus:ring-4 focus:ring-blue-500/20 text-white py-3 text-sm font-medium transition hover:-translate-y-0.5">

            Create account

        </button>

    </form>

    <!-- Footer -->
    <p class="mt-6 text-center text-sm text-slate-500">
        Already have an account?
        <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium">
            Sign in
        </a>
    </p>

</x-auth-layout>