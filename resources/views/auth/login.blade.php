<x-auth-layout title="Bon retour" subtitle="Connectez-vous pour accéder à votre tableau de bord.">

    @if(session('status'))
        <div role="status" class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <x-input name="email" type="email" label="Adresse email"
                 autocomplete="username" inputmode="email" placeholder="vous@exemple.com"
                 required autofocus />

        <x-password-input name="password" label="Mot de passe"
                          autocomplete="current-password" placeholder="Votre mot de passe" required />

        <div class="flex items-center justify-between gap-4">
            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-stone-600 dark:text-stone-400">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                       class="h-4 w-4 rounded border-stone-300 text-amber-500 focus:ring-amber-400 dark:border-stone-600 dark:bg-stone-800">
                Se souvenir de moi
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm font-medium text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit" :disabled="loading"
                class="flex h-12 w-full items-center justify-center rounded-xl bg-stone-900 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-stone-900/20 disabled:cursor-wait disabled:opacity-70 dark:bg-amber-400 dark:text-stone-900 dark:hover:bg-amber-300">
            <span x-show="!loading">Se connecter</span>
            <span x-show="loading" x-cloak>Connexion en cours…</span>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-stone-500 dark:text-stone-400">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-semibold text-stone-900 hover:underline dark:text-white">Créer un compte</a>
    </p>
</x-auth-layout>