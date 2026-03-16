<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('transfer.create') }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                สินค้าระหว่างทาง (Transit)
            </h2>
        </div>
    </x-slot>

    <div class="py-6 w-full relative z-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                    <div class="bg-emerald-100 rounded-lg p-1 text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div>
                        <p class="font-bold">สำเร็จ!</p>
                        <p class="text-sm font-medium opacity-90">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                    <div class="bg-rose-100 rounded-lg p-1 text-rose-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div>
                        <p class="font-bold">พบข้อผิดพลาด</p>
                        <ul class="list-disc list-inside space-y-0.5 mt-1 font-medium text-sm opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Summary KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 bg-gradient-to-br from-orange-400 to-rose-500 rounded-[2rem] p-6 shadow-lg shadow-orange-500/20 text-white flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute right-0 top-0 bottom-0 w-48 bg-white/10 rounded-full blur-[40px] -z-10 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                    <div>
                        <p class="text-sm font-semibold text-orange-100 uppercase tracking-widest mb-1">สินค้าที่กำลังจัดส่ง</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black tracking-tight">{{ $stocksInTransit->count() }}</span>
                            <span class="text-lg font-medium text-orange-200">รายการ</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl border border-white/10 shadow-inner group-hover:-translate-y-1 transition-transform">🚚</div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex items-center justify-between group hover:border-slate-300 transition-colors">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-400"></span> จำนวนชิ้นทั้งหมดใน Transit</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($stocksInTransit->sum('quantity')) }}</span>
                            <span class="text-sm font-semibold text-slate-400">ชิ้น</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl shadow-sm border border-blue-100 group-hover:-rotate-6 transition-transform">📦</div>
                </div>
            </div>

            {{-- Main Data Grid --}}
            <div class="bg-white rounded-[2rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden flex flex-col min-h-[400px]">
                
                {{-- Header --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="font-bold text-xl text-slate-800 tracking-tight flex items-center gap-2">
                        <span class="w-8 h-8 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg shadow-sm border border-indigo-200">📋</span> รายการที่รอรับเข้า
                    </h3>
                    <a href="{{ route('transfer.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-sm w-full sm:w-auto group">
                        <span class="group-hover:-translate-x-1 transition-transform">←</span> สร้างรายการย้ายเพิ่ม
                    </a>
                </div>

                @if($stocksInTransit->isEmpty())
                <div class="p-16 text-center flex-1 flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-inner">
                        <span class="text-4xl block">✨</span>
                    </div>
                    <p class="text-slate-700 text-xl font-bold mb-1">ไม่มีสินค้าระหว่างทาง</p>
                    <p class="text-slate-400 text-sm">สินค้าทั้งหมดถูกรับเข้าสู่คลังเรียบร้อยแล้ว</p>
                </div>
                @else
                {{-- Data Grid --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-white text-[10px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">ข้อมูลสินค้า</th>
                                <th class="px-6 py-4 text-center">จำนวน</th>
                                <th class="px-6 py-4">เส้นทางย้าย</th>
                                <th class="px-6 py-4">การทำรายการ</th>
                                <th class="px-6 py-4 bg-slate-50/50 rounded-tl-xl text-center">ดำเนินการรับเข้า</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($stocksInTransit as $st)
                                @php
                                    $tx = $pendingTransactions->firstWhere('product_id', $st->product_id);
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    {{-- Product Info --}}
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-800 text-base leading-tight mb-1">{{ $st->product->name }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-mono font-bold text-slate-600 bg-white border border-slate-200 px-1.5 py-0.5 rounded shadow-sm">{{ $st->product->sku ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="px-6 py-5 text-center">
                                        <div class="inline-flex items-center justify-center min-w-[60px] bg-orange-50 text-orange-600 border border-orange-200 px-3 py-1 rounded-xl font-black text-lg shadow-sm group-hover:scale-105 transition-transform">
                                            {{ $st->quantity }}
                                        </div>
                                    </td>

                                    {{-- Route (From -> To) --}}
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest w-12">จาก</span>
                                                @if($tx && $tx->fromLocation)
                                                    <span class="font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">{{ $tx->fromLocation->name }}</span>
                                                @else
                                                    <span class="text-slate-400">-</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest w-12">ไปยัง</span>
                                                @if($tx && $tx->toLocation)
                                                    <span class="font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md shadow-sm">{{ $tx->toLocation->name }}</span>
                                                @else
                                                    <span class="text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md text-xs font-bold shadow-sm">ยังไม่ระบุ</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Transaction Details --}}
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1.5">
                                            @if($tx && $tx->user)
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">U</span>
                                                    <span class="font-medium text-slate-700 text-xs">{{ $tx->user->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                            
                                            @if($tx)
                                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <div>
                                                        <span class="font-bold border-b border-slate-300">{{ $tx->created_at->diffForHumans() }}</span>
                                                        <span class="text-[10px] block mt-0.5 opacity-70">{{ $tx->created_at->format('d/m/y H:i') }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Action Form --}}
                                    <td class="px-6 py-5 bg-slate-50/50 border-l border-slate-100">
                                        <form action="{{ route('transfer.receive') }}" method="POST" class="flex flex-col xl:flex-row gap-2 w-full max-w-sm mx-auto">
                                            @csrf
                                            <input type="hidden" name="stock_id" value="{{ $st->id }}">
                                            
                                            <select name="to_location_id" class="flex-1 text-sm border-slate-200 rounded-xl focus:border-emerald-400 focus:ring focus:ring-emerald-200/50 bg-white py-2.5 px-3 font-semibold shadow-sm transition-all outline-none" required>
                                                @foreach($destinations as $dest)
                                                    <option value="{{ $dest->id }}"
                                                        {{ ($tx && $tx->to_location_id == $dest->id) ? 'selected' : '' }}>
                                                        {{ $dest->name }} {{ $dest->zone ? '('.$dest->zone.')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit"
                                                onclick="return confirm('⚠️ ยืนยันรับสินค้าเข้าตำแหน่งนี้ใช่หรือไม่?')"
                                                class="bg-gradient-to-br from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-md transition-all hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-1.5 whitespace-nowrap">
                                                ✅ รับเข้าสต็อก
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>