<section class="space-y-6">
    <header>
        <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-sm">🗑️</span> {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-sm font-medium text-slate-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-rose-500/20 hover:shadow-lg hover:shadow-rose-500/40 hover:-translate-y-0.5 transition-all text-sm mt-4"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="text-rose-500">⚠️</span> {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm font-medium text-slate-500">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full sm:w-3/4 border border-slate-200 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-rose-200 focus:border-rose-400 focus:bg-white transition-all shadow-sm"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-sm text-rose-500 font-medium border border-rose-100 bg-rose-50 px-3 py-1.5 rounded-lg w-max" />
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-2.5 px-6 rounded-xl shadow-sm transition-all text-sm">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-rose-500/20 hover:shadow-lg hover:shadow-rose-500/40 hover:-translate-y-0.5 transition-all text-sm">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
