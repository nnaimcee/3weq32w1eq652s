<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="text-2xl">👤</span> {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 relative z-10 w-full overflow-x-hidden">
        <div class="absolute top-0 right-0 w-full h-[300px] bg-gradient-to-b from-indigo-50/50 to-transparent -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white sm:rounded-[1.5rem] relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white sm:rounded-[1.5rem] relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-cyan-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white sm:rounded-[1.5rem] relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-50 to-orange-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
