<x-auth-layout title="Créer votre compte" subtitle="Quelques secondes suffisent. Vous créerez ensuite votre salle.">

    <form method="POST" action="{{ route('register') }}" class="space-y-5"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <x-input name="name" label="Nom complet"
                 autocomplete="name" placeholder="Prénom Nom" required autofocus />

        <x-input name="email" type="email" label="Adresse email"
                 autocomplete="username" inputmode="email" placeholder="vous@exemple.com" required />

        <x-password-input name="password" label="Mot de passe"
                          autocomplete="new-password" placeholder="8 caractères minimum" required />

        <x-password-input name="password_confirmation" label="Confirmer le mot de passe"
                          autocomplete="new-password" placeholder="Retapez le mot de passe" required />

        <div class="space-y-1.5">
            <label class="flex cursor-pointer items-start gap-3 text-sm leading-relaxed text-stone-600 dark:text-stone-400">
                <input type="checkbox" name="terms" value="1" @checked(old('terms')) required
                       class="mt-0.5 h-4 w-4 shrink-0 rounded border-stone-300 text-amber-500 focus:ring-amber-400 dark:border-stone-600 dark:bg-stone-800">
                <span>
                    J'accepte les
                    <a href="{{ url('/conditions') }}" class="font-medium text-stone-900 underline-offset-2 hover:underline dark:text-white">conditions d'utilisation</a>
                    et la
                    <a href="{{ url('/politique-de-confidentialite') }}" class="font-medium text-stone-900 underline-offset-2 hover:underline dark:text-white">politique de confidentialité</a>.
                </span>
            </label>
            @error('terms')
                <p class="text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" :disabled="loading"
                class="flex h-12 w-full items-center justify-center rounded-xl bg-stone-900 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-stone-900/20 disabled:cursor-wait disabled:opacity-70 dark:bg-amber-400 dark:text-stone-900 dark:hover:bg-amber-300">
            <span x-show="!loading">Créer mon compte</span>
            <span x-show="loading" x-cloak>Création en cours…</span>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-stone-500 dark:text-stone-400">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}" class="font-semibold text-stone-900 hover:underline dark:text-white">Se connecter</a>
    </p>
</x-auth-layout>