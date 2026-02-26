<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 mr-2">←</a>
            ➕ เพิ่มผู้ใช้งานใหม่
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-6 border-t-4 border-indigo-500">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <!-- ชื่อ -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-bold text-gray-600 mb-1">ชื่อผู้ใช้ (Name) <span class="text-red-500">*</span></label>
                        <input id="name" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" value="{{ old('name') }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- อีเมล -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-bold text-gray-600 mb-1">อีเมล (Email) <span class="text-red-500">*</span></label>
                        <input id="email" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" value="{{ old('email') }}" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- สิทธิ์การใช้งาน -->
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-bold text-gray-600 mb-1">สิทธิ์การใช้งาน <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="" disabled selected>-- เลือกสิทธิ์ --</option>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>👤 Staff</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-600 mb-1">รหัสผ่าน <span class="text-red-500">*</span></label>
                            <input id="password" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-600 mb-1">ยืนยันรหัสผ่าน <span class="text-red-500">*</span></label>
                            <input id="password_confirmation" class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition">
                        💾 บันทึกผู้ใช้งาน
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
