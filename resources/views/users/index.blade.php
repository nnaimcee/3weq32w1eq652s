<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👥 จัดการผู้ใช้งาน (Users)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-xl shadow-lg transition">
                    ➕ เพิ่มผู้ใช้งานใหม่
                </a>
            </div>

            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-4 bg-gray-50 border-b flex items-center justify-between">
                    <h3 class="font-bold text-gray-700">📋 รายการผู้ใช้งานทั้งหมด ({{ $users->count() }} คน)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">ชื่อผู้ใช้</th>
                                <th class="px-4 py-3">อีเมล</th>
                                <th class="px-4 py-3">สิทธิ์การใช้งาน (Role)</th>
                                <th class="px-4 py-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-bold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        @if($user->role === 'admin')
                                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">👑 Admin</span>
                                        @else
                                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">👤 Staff</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-1">
                                            <a href="{{ route('users.edit', $user->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold py-1 px-3 rounded-full transition" title="แก้ไข">
                                                ✏️ แก้ไข
                                            </a>
                                            
                                            @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ ยืนยันการลบผู้ใช้งาน {{ $user->name }} หรือไม่? ข้อมูลนี้ไม่สามารถกู้คืนได้');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold py-1 px-2 transition" title="ลบ">
                                                    🗑️
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-gray-300 py-1 px-2 cursor-not-allowed" title="ไม่สามารถลบตัวเองได้">
                                                🗑️
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
