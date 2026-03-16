<section>
    <header>
        <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-sm">🔑</span> {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm font-medium text-slate-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="font-bold text-slate-700" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full border border-slate-200 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400 focus:bg-white transition-all shadow-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="font-bold text-slate-700" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-2 block w-full border border-slate-200 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400 focus:bg-white transition-all shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="font-bold text-slate-700" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full border border-slate-200 rounded-xl px-4 py-3 bg-white/50 focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400 focus:bg-white transition-all shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-cyan-500/20 hover:shadow-lg hover:shadow-cyan-500/40 hover:-translate-y-0.5 transition-all text-sm">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-1.5"
                ><span class="text-xs">✅</span> {{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
