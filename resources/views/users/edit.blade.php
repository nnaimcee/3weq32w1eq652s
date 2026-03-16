<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 mr-2">←</a>
            ✏️ แก้ไขข้อมูลผู้ใช้งาน: <span class="text-indigo-600">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full overflow-x-hidden">
            <div class="absolute top-0 right-0 w-full h-[300px] bg-gradient-to-b from-indigo-50/50 to-transparent -z-10 blur-3xl pointer-events-none"></div>

            <div class="bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white rounded-[1.5rem] p-6 lg:p-8 relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- ชื่อ -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">ชื่อผู้ใช้ (Name) <span class="text-rose-500">*</span></label>
                        <input id="name" class="w-full border border-slate-200 rounded-xl text-sm px-4 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 focus:bg-white transition-all shadow-sm" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100" />
                    </div>

                    <!-- อีเมล -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">อีเมล (Email) <span class="text-rose-500">*</span></label>
                        <input id="email" class="w-full border border-slate-200 rounded-xl text-sm px-4 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 focus:bg-white transition-all shadow-sm" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100" />
                    </div>

                    <!-- สิทธิ์การใช้งาน -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-bold text-slate-700 mb-2">สิทธิ์การใช้งาน <span class="text-rose-500">*</span></label>
                        <select id="role" name="role" class="w-full border border-slate-200 rounded-xl text-sm px-4 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 focus:bg-white transition-all shadow-sm" required>
                            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>👤 Staff</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>👑 Admin</option>
                        </select>
                        
                        @if(auth()->id() == $user->id)
                            <div class="mt-3 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm relative flex gap-2">
                                <span class="text-lg leading-none">⚠️</span>
                                <div>
                                    <b class="block mb-0.5">ข้อควรระวัง:</b> คุณกำลังแก้ไขสิทธิ์ของตัวเอง หากเปลี่ยนเป็น Staff คุณจะไม่สามารถเข้าถึงเมนู Admin ได้อีก
                                </div>
                            </div>
                        @endif
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100" />
                    </div>

                    <div class="my-8 pt-8 border-t border-slate-100 relative">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white px-4 text-center">
                            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest bg-slate-50 border border-slate-100 px-3 py-1 rounded-full"><span class="text-base mr-1">🔐</span> รหัสผ่าน</h3>
                        </div>

                        <div class="mt-6 mb-4 text-center">
                            <p class="text-xs text-slate-500 font-medium bg-slate-50 inline-block px-3 py-1 rounded-full border border-slate-100">ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-2">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">รหัสผ่านใหม่</label>
                                <input id="password" class="w-full border border-slate-200 rounded-xl text-sm px-4 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-indigo-400 focus:bg-white transition-all shadow-sm" type="password" name="password" autocomplete="new-password" placeholder="ตั้งรหัสผ่านใหม่" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                                <input id="password_confirmation" class="w-full border border-slate-200 rounded-xl text-sm px-4 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-indigo-400 focus:bg-white transition-all shadow-sm" type="password" name="password_confirmation" placeholder="พิมพ์ซ้ำอีกครั้ง" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-500 font-medium bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <button type="submit" class="w-full sm:w-auto min-w-[200px] bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/40 transition-all hover:-translate-y-0.5 text-base sm:text-lg flex justify-center items-center gap-2">
                            💾 อัปเดตข้อมูลผู้ใช้งาน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
