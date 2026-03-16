<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-10 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 mb-6 shadow-inner border border-indigo-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-2">ยินดีต้อนรับกลับมา 🚀</h2>
        <p class="text-slate-500 font-medium">เข้าสู่ระบบ WMS เพื่อจัดการคลังสินค้าของคุณ</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">อีเมล (Email)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="block w-full pl-12 pr-4 py-3.5 border border-slate-200 rounded-xl text-base font-semibold text-slate-800 bg-slate-50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner"
                    placeholder="admin@wms.com / staff@wms.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg w-max border border-rose-100" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-bold text-slate-700">รหัสผ่าน (Password)</label>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full pl-12 pr-4 py-3.5 border border-slate-200 rounded-xl text-base font-semibold text-slate-800 bg-slate-50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg w-max border border-rose-100" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-5 h-5 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500/30 transition-all cursor-pointer">
                <span class="ml-2 text-sm font-semibold text-slate-600 group-hover:text-slate-800 transition-colors select-none">จดจำฉันไว้</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                    ลืมรหัสผ่าน?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-base font-bold text-white shadow-lg shadow-indigo-500/30 transition-all hover:shadow-xl hover:-translate-y-0.5 mt-4"
            style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            เข้าสู่ระบบ
        </button>

        <div class="pt-8 mt-8 border-t border-slate-100 text-center">
            <p class="text-sm font-medium text-slate-500">ไม่ได้เป็นสมาชิก? <span class="font-bold text-slate-700">ติดต่อแอดมิน เพื่อขอสิทธิ์</span></p>
        </div>
    </form>
</x-guest-layout>
