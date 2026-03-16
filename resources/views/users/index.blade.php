<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👥 จัดการผู้ใช้งาน (Users)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full overflow-x-hidden">
            <div class="absolute top-0 right-0 w-full h-[300px] bg-gradient-to-b from-indigo-50/50 to-transparent -z-10 blur-3xl pointer-events-none"></div>
            
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-2">
                    <span class="text-emerald-500">✅</span> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                    <ul class="list-disc list-inside space-y-0.5 mt-1 font-medium text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="flex justify-end mb-6">
                <a href="{{ route('users.create') }}" class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2">
                    <span class="bg-white/20 rounded-lg w-6 h-6 flex items-center justify-center text-xs">+</span> เพิ่มผู้ใช้งานใหม่
                </a>
            </div>

            <div class="bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white rounded-[1.5rem] overflow-hidden">
                <div class="p-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-black text-slate-700 flex items-center gap-2"><span class="w-6 h-6 bg-slate-200 text-slate-500 rounded-lg text-center flex items-center justify-center text-xs">📋</span> รายการผู้ใช้งานทั้งหมด ({{ $users->count() }} คน)</h3>
                </div>

                {{-- Mobile Card View --}}
                <div class="sm:hidden divide-y divide-slate-100">
                    @foreach($users as $user)
                        <div class="p-5 hover:bg-slate-50/50 transition-colors relative group">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 border border-white shadow-sm flex items-center justify-center text-indigo-500 font-black text-lg">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-base mb-0.5">{{ $user->name }}</p>
                                        <p class="text-[13px] text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if($user->role === 'admin')
                                    <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider flex items-center justify-center"><span class="text-[10px] mr-1">👑</span> Admin</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider flex items-center justify-center"><span class="text-[10px] mr-1">👤</span> Staff</span>
                                @endif
                            </div>
                            <div class="flex gap-2 mt-4 pt-4 border-t border-slate-100">
                                <a href="{{ route('users.edit', $user->id) }}" class="flex-1 bg-white border border-amber-200 hover:bg-amber-50 hover:border-amber-300 text-amber-600 text-[13px] font-bold py-2 rounded-xl transition-all shadow-sm text-center flex items-center justify-center gap-1">
                                    ✏️ แก้ไข
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="flex-1" onsubmit="return confirm('⚠️ ยืนยันการลบผู้ใช้งาน {{ $user->name }} หรือไม่?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-white border border-rose-200 hover:bg-rose-50 hover:border-rose-300 text-rose-600 text-[13px] font-bold py-2 rounded-xl transition-all shadow-sm flex items-center justify-center gap-1">
                                        🗑️ ลบ
                                    </button>
                                </form>
                                @else
                                <div class="flex-1 flex items-center justify-center text-[13px] text-slate-400 font-medium bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    บัญชีของคุณ
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-black tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-4">ผู้ใช้งาน</th>
                                <th class="px-5 py-4">สิทธิ์การใช้งาน</th>
                                <th class="px-5 py-4 text-right">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 border border-white shadow-sm flex items-center justify-center text-indigo-500 font-black text-lg">
                                                {{ mb_substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-base mb-0.5">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($user->role === 'admin')
                                            <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1 w-max"><span class="text-[10px]">👑</span> Admin</span>
                                        @else
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1 w-max"><span class="text-[10px]">👤</span> Staff</span>
                                        @endif
                                        @if(auth()->id() === $user->id)
                                            <span class="ml-2 text-[10px] text-slate-400 font-medium uppercase tracking-wider bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">(คุณ)</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex justify-end gap-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('users.edit', $user->id) }}" class="w-8 h-8 rounded-xl bg-white border border-amber-200 text-amber-600 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center shadow-sm transition-all hover:-translate-y-0.5" title="แก้ไข">
                                                ✏️
                                            </a>
                                            
                                            @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ ยืนยันการลบผู้ใช้งาน {{ $user->name }} หรือไม่? ข้อมูลนี้ไม่สามารถกู้คืนได้');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-xl bg-white border border-rose-200 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center shadow-sm transition-all hover:-translate-y-0.5" title="ลบ">
                                                    🗑️
                                                </button>
                                            </form>
                                            @else
                                            <span class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 text-slate-300 flex items-center justify-center cursor-not-allowed" title="ไม่สามารถลบตัวเองได้">
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
