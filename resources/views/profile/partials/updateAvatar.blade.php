<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('avatar') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div>
            <x-input-label for="name" :value="__('Photo')" />
            <x-text-input id="name" name="avatar" type="file" class="mt-1 block w-full px-5 py-3 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 pr-12 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-800 outline-none transition file:mr-4 file:py-2 file:px-4 
            file:roundes-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950 dark:file:text-indigo-300 dark:hover:file:bg-indigo900"     />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
