<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-sm border border-indigo-200">📜</div>
                <div>
                    <h2 class="font-bold text-xl text-slate-800 leading-tight">ประวัติความเคลื่อนไหว</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Transaction History</p>
                </div>
            </div>
            <div>
                <a href="{{ route('transactions.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-bold py-2 px-4 rounded-xl shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    รีเฟรชข้อมูล
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 w-full relative z-10 overflow-x-hidden">
        <!-- Abstract Background -->
        <div class="fixed top-0 left-0 w-full h-[600px] bg-gradient-to-br from-indigo-50/60 via-slate-50/50 to-blue-50/40 -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Summary KPIs --}}
            @php
                $countIn = \App\Models\Transaction::where('type','IN')->count();
                $countOut = \App\Models\Transaction::where('type','OUT')->count();
                $countTransfer = \App\Models\Transaction::where('type','TRANSFER')->count();
                $countReserve = \App\Models\Transaction::where('type','RESERVE')->count();
                $countRelease = \App\Models\Transaction::where('type','RELEASE')->count();
                $countPending = \App\Models\Transaction::where('status','pending')->count();
            @endphp
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4 relative z-10">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white p-4 text-center hover:-translate-y-0.5 transition-transform group">
                    <div class="w-8 h-8 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">📥</div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">รับเข้า</p>
                    <p class="text-xl font-black text-emerald-600">{{ number_format($countIn) }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white p-4 text-center hover:-translate-y-0.5 transition-transform group">
                    <div class="w-8 h-8 mx-auto rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">📤</div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">เบิกออก</p>
                    <p class="text-xl font-black text-rose-600">{{ number_format($countOut) }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white p-4 text-center hover:-translate-y-0.5 transition-transform group">
                    <div class="w-8 h-8 mx-auto rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">🚚</div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">ย้ายของ</p>
                    <p class="text-xl font-black text-blue-600">{{ number_format($countTransfer) }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white p-4 text-center hover:-translate-y-0.5 transition-transform group">
                    <div class="w-8 h-8 mx-auto rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">🔒</div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">จอง</p>
                    <p class="text-xl font-black text-amber-600">{{ number_format($countReserve) }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white p-4 text-center hover:-translate-y-0.5 transition-transform group">
                    <div class="w-8 h-8 mx-auto rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">🔓</div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">ปลดจอง</p>
                    <p class="text-xl font-black text-purple-600">{{ number_format($countRelease) }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border {{ $countPending > 0 ? 'border-orange-200 shadow-orange-500/10' : 'border-white' }} p-4 text-center hover:-translate-y-0.5 transition-transform group">
                    <div class="w-8 h-8 mx-auto rounded-full {{ $countPending > 0 ? 'bg-orange-50 text-orange-600' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform">⏳</div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">รอดำเนินการ</p>
                    <p class="text-xl font-black {{ $countPending > 0 ? 'text-orange-500' : 'text-slate-400' }}">{{ number_format($countPending) }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 backdrop-blur-xl p-2 rounded-[1.5rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white flex flex-col sm:flex-row items-center gap-2 sm:gap-4 justify-between relative z-10 w-full overflow-x-auto no-scrollbar">
                <div class="flex items-center gap-2 px-3 py-2 shrink-0">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Filter:</span>
                </div>
                <div class="flex gap-1.5 sm:gap-2 pb-1 sm:pb-0 overflow-x-auto w-full sm:w-auto shrink-0">
                    <a href="{{ route('transactions.index') }}" class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border {{ !request('type') ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        ทั้งหมด
                    </a>
                    <a href="{{ route('transactions.index', ['type' => 'IN']) }}" class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border {{ request('type') == 'IN' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-emerald-700 border-emerald-200 hover:bg-emerald-50' }}">
                        📥 รับเข้า
                    </a>
                    <a href="{{ route('transactions.index', ['type' => 'OUT']) }}" class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border {{ request('type') == 'OUT' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-rose-700 border-rose-200 hover:bg-rose-50' }}">
                        📤 เบิกออก
                    </a>
                    <a href="{{ route('transactions.index', ['type' => 'TRANSFER']) }}" class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border {{ request('type') == 'TRANSFER' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-700 border-blue-200 hover:bg-blue-50' }}">
                        🚚 ย้าย
                    </a>
                    <a href="{{ route('transactions.index', ['type' => 'RESERVE']) }}" class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border {{ request('type') == 'RESERVE' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50' }}">
                        🔒 จอง
                    </a>
                    <a href="{{ route('transactions.index', ['type' => 'RELEASE']) }}" class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border {{ request('type') == 'RELEASE' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-purple-700 border-purple-200 hover:bg-purple-50' }}">
                        🔓 ปลดจอง
                    </a>
                </div>
            </div>

            {{-- Transaction Activity Feed / Bank Statement Style --}}
            <div class="bg-white/90 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white rounded-[2rem] overflow-hidden relative z-10">
                <div class="px-6 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-800 tracking-tight text-lg">รายการธุรกรรมล่าสุด</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $transactions->total() }} Records</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        @php
                            $typeConfig = match($trx->type) {
                                'IN' => ['icon' => '📥', 'label' => 'รับเข้า', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'color' => 'text-emerald-600', 'sign' => '+'],
                                'OUT' => ['icon' => '📤', 'label' => 'เบิกออก', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200', 'color' => 'text-rose-600', 'sign' => '-'],
                                'TRANSFER' => ['icon' => '🚚', 'label' => 'ย้ายของ', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200', 'color' => 'text-blue-600', 'sign' => ''],
                                'RESERVE' => ['icon' => '🔒', 'label' => 'จองสินค้า', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'color' => 'text-amber-600', 'sign' => ''],
                                'RELEASE' => ['icon' => '🔓', 'label' => 'ปลดจอง', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200', 'color' => 'text-purple-600', 'sign' => ''],
                                default => ['icon' => '📋', 'label' => $trx->type, 'badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'color' => 'text-slate-600', 'sign' => ''],
                            };
                        @endphp
                        
                        <div class="p-5 sm:p-6 hover:bg-slate-50/80 transition-colors group flex flex-col sm:flex-row sm:items-center gap-4">
                            
                            {{-- Mobile: Date Top Right (absolute visual) --}}
                            <div class="w-full flex justify-between sm:hidden mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest border {{ $typeConfig['badge'] }}">{{ $typeConfig['label'] }}</span>
                                    @if($trx->status === 'pending')
                                        <span class="bg-orange-100 text-orange-600 border border-orange-200 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest animate-pulse">⏳ รอดำเนินการ</span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-slate-400">{{ $trx->created_at->format('H:i') }}</p>
                                    <p class="text-[10px] font-bold text-slate-400">{{ $trx->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            {{-- Left side: Icon & Product --}}
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-2xl shadow-sm shrink-0 relative">
                                    {{ $typeConfig['icon'] }}
                                    @if($trx->status === 'completed')
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center"><svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                    @endif
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-2 mb-1 hidden sm:flex">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest border {{ $typeConfig['badge'] }}">{{ $typeConfig['label'] }}</span>
                                        @if($trx->status === 'pending')
                                            <span class="bg-orange-100 text-orange-600 border border-orange-200 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest animate-pulse">⏳ รอดำเนินการ</span>
                                        @endif
                                    </div>
                                    
                                    <h4 class="font-bold text-slate-800 text-base leading-tight">{{ $trx->product->name ?? '-' }}</h4>
                                    <p class="text-[11px] font-mono font-bold text-slate-500 mb-2">{{ $trx->product->sku ?? '-' }}</p>

                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
                                        @if($trx->fromLocation)
                                            <span class="text-slate-500 flex items-center gap-1 font-medium bg-slate-100/80 px-2 py-0.5 rounded-md border border-slate-200/60">
                                                <span class="text-slate-400">จาก:</span> 
                                                <span class="font-bold text-slate-700">{{ $trx->fromLocation->name }} <span class="text-[10px] font-normal">({{ $trx->fromLocation->zone }})</span></span>
                                            </span>
                                        @endif

                                        @if($trx->toLocation)
                                            <span class="text-slate-500 flex items-center gap-1 font-medium bg-slate-100/80 px-2 py-0.5 rounded-md border border-slate-200/60">
                                                <span class="text-slate-400">ไป:</span> 
                                                <span class="font-bold text-slate-700">{{ $trx->toLocation->name }} <span class="text-[10px] font-normal">({{ $trx->toLocation->zone }})</span></span>
                                            </span>
                                        @endif

                                        @if($trx->ref_doc_no)
                                            <span class="text-slate-500 flex items-center gap-1 font-medium bg-slate-100/80 px-2 py-0.5 rounded-md border border-slate-200/60">
                                                <span class="text-slate-400 text-[10px] uppercase tracking-widest font-black">Ref:</span> 
                                                <span class="font-mono font-bold text-slate-700">{{ $trx->ref_doc_no }}</span>
                                            </span>
                                        @endif
                                        
                                        @if($trx->lot_number)
                                            <span class="text-slate-500 flex items-center gap-1 font-medium bg-slate-100/80 px-2 py-0.5 rounded-md border border-slate-200/60">
                                                <span class="text-blue-500 text-[10px] uppercase tracking-widest font-black">Lot:</span> 
                                                <span class="font-mono font-bold text-slate-700">{{ $trx->lot_number }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($trx->notes)
                                        <p class="text-[11px] text-slate-500 mt-2 font-medium flex items-center gap-1"><span class="text-indigo-400 text-sm">💬</span> {{ $trx->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Right side: Amount & User/Time (Desktop) --}}
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0 mt-2 sm:mt-0">
                                <div class="text-left sm:text-right">
                                    <div class="flex items-baseline gap-1 {{ $typeConfig['color'] }}">
                                        <span class="text-xl sm:text-2xl font-black font-mono leading-none">{{ $typeConfig['sign'] }}{{ number_format($trx->quantity) }}</span>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">ชิ้น</span>
                                    </div>
                                </div>
                                <div class="text-right hidden sm:block mt-2">
                                    <p class="text-[11px] font-bold text-slate-600">{{ $trx->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mb-1">{{ $trx->created_at->format('H:i:s') }}</p>
                                    <p class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded inline-block">👤 {{ $trx->user->name ?? 'System' }}</p>
                                </div>
                                {{-- Mobile User --}}
                                <div class="block sm:hidden text-right">
                                    <p class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded inline-block">👤 {{ $trx->user->name ?? 'System' }}</p>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="p-12 text-center flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-4xl mb-4 border border-slate-100 shadow-inner">📭</div>
                            <h4 class="text-lg font-black text-slate-800 tracking-tight">ยังไม่มีประวัติความเคลื่อนไหว</h4>
                            <p class="text-sm text-slate-500 font-medium max-w-sm mt-1">ประวัติการรับเข้า, เบิกออก, ย้าย, และการจองสินค้าจะแสดงที่นี่</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>