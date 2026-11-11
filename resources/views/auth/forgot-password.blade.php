@extends('layouts.fullscreen-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 py-12 bg-gray-50 dark:bg-gray-950 transition-colors duration-300">

    <div class="max-w-md w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm p-8">

        {{-- En-tête : Icône Cadenas et Titres centrés --}}
        <div class="text-center mb-8">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875A4.875 4.875 0 0011.625 3a4.875 4.875 0 00-4.875 4.875V10.5m9.75 0h.75A1.5 1.5 0 0118.75 12v6A1.5 1.5 0 0117.25 19.5h-11A1.5 1.5 0 014.75 18v-6a1.5 1.5 0 011.5-1.5H7" />
                </svg>
            </div>

            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                Recover Password
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                Enter the email address associated with your account and we'll send you a secure password reset link.
            </p>
        </div>

        {{-- Message de succès (Alerte subtile) --}}
        @if(session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 p-4 text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">
                    {{ session('status') }}
                </p>
            </div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Email Address
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12H8m8-4H8m8 8H8"/>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com"
                        class="block w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 pl-11 pr-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors shadow-sm outline-none">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gray-900 dark:bg-white px-5 py-3 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Send Reset Link
            </button>
        </form>

        {{-- Lien retour minimaliste --}}
        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sign In
            </a>
        </div>
    </div>

    {{-- Theme Switcher (Épuré, sans ombres massives) --}}
    <div class="fixed bottom-6 right-6 z-50">
        <button x-data @click.prevent="$store.theme.toggle()" class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white shadow-sm transition-colors focus:outline-none">
            {{-- Icône Soleil --}}
            <svg class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            {{-- Icône Lune --}}
            <svg class="hidden h-5 w-5 dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

</div>
@endsection