<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                แผนผังคลังสินค้า (Inventory Map)
            </h2>
        </div>
    </x-slot>

    <div class="py-6 w-full relative z-10 overflow-x-hidden">
        <!-- Abstract Background -->
        <div class="fixed top-0 right-0 w-full h-[500px] bg-gradient-to-b from-indigo-50/60 via-purple-50/30 to-transparent -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Bar --}}
            @php
                $allLocations = collect();
                foreach ($zones as $locs) { $allLocations = $allLocations->merge($locs); }
                $totalLocations = $allLocations->count();
                $full = $allLocations->filter(fn($l) => $l->status === 'full')->count();
                $occupied = $allLocations->filter(fn($l) => $l->stocks->sum('quantity') > 0 && $l->status !== 'full')->count();
                $reserved = $allLocations->filter(fn($l) => $l->stocks->sum('quantity') == 0 && $l->stocks->sum('reserved_qty') > 0 && $l->status !== 'full')->count();
                $empty = $totalLocations - $full - $occupied - $reserved;
                $totalItems = $allLocations->sum(fn($l) => $l->stocks->sum('quantity'));
                $totalReservedCount = $allLocations->sum(fn($l) => $l->stocks->sum('reserved_qty'));
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Total Locations -->
                <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[1.5rem] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-slate-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-sm shadow-inner tracking-widest border border-slate-200">📍</div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">ทั้งหมด</h3>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalLocations }}</span>
                    </div>
                </div>

                <!-- Empty -->
                <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[1.5rem] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shadow-inner border border-emerald-200">🟢</div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">ว่าง</h3>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-emerald-600 tracking-tight">{{ $empty }}</span>
                    </div>
                </div>

                <!-- Occupied -->
                <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[1.5rem] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-blue-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm shadow-inner border border-blue-200">📦</div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">มีสินค้า</h3>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-blue-600 tracking-tight">{{ $occupied }}</span>
                    </div>
                </div>

                 <!-- Reserved -->
                 <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[1.5rem] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-yellow-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-sm shadow-inner border border-yellow-200">🏷️</div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">กันสต็อก</h3>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-yellow-600 tracking-tight">{{ number_format($totalReservedCount) }}</span>
                    </div>
                </div>

                <!-- Full -->
                <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[1.5rem] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-orange-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-sm shadow-inner border border-orange-200">🛑</div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">เต็มแล้ว</h3>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-orange-600 tracking-tight">{{ $full }}</span>
                    </div>
                </div>

                <!-- Total Items -->
                <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[1.5rem] p-5 shadow-[0_8px_30px_rgba(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-indigo-100 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm shadow-inner border border-indigo-200">📊</div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">สินค้ารวม</h3>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-indigo-600 tracking-tight">{{ number_format($totalItems) }}</span>
                    </div>
                </div>
            </div>

            {{-- Legend --}}
            <div class="bg-white/60 backdrop-blur-md p-4 rounded-[1rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-white/80 flex flex-wrap justify-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/50"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">ว่าง</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">มีสินค้า</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 shadow-sm shadow-yellow-400/50"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">กันสต็อก</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-500 shadow-sm shadow-purple-500/50"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">จองรอเข้า</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-orange-500 shadow-sm shadow-orange-500/50"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">เต็ม</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">ปิดจุด</span>
                </div>
            </div>

            {{-- Zone Sections --}}
            @foreach($zones as $zoneName => $locations)
            @php
                $zoneQty = $locations->sum(fn($l) => $l->stocks->sum('quantity'));
                $zoneReserved = $locations->sum(fn($l) => $l->stocks->sum('reserved_qty'));
                $zoneOccupied = $locations->filter(fn($l) => $l->stocks->sum('quantity') > 0)->count();
            @endphp
            <div class="pt-6 relative">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 pb-2 border-b border-slate-200 gap-3">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Zone: {{ $zoneName }}</h3>
                            <span class="text-xs font-bold bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full border border-slate-200">{{ $locations->count() }} จุด</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs font-bold text-slate-600 mb-1">
                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg border border-blue-100 flex items-center gap-1.5"><span class="opacity-70">📦</span> {{ number_format($zoneQty) }} ชิ้น</span>
                        <span class="bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-lg border border-yellow-100 flex items-center gap-1.5"><span class="opacity-70">🏷️</span> จอง {{ number_format($zoneReserved) }}</span>
                        <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg border border-emerald-100 flex items-center gap-1.5"><span class="opacity-70">📍</span> ใช้ {{ $zoneOccupied }}/{{ $locations->count() }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($locations as $loc)
                        @php
                            $totalQty = $loc->stocks->sum('quantity');
                            $totalReservedLoc = $loc->stocks->sum('reserved_qty');
                            $availableLoc = $totalQty - $totalReservedLoc;
                            $productCount = $loc->stocks->where('quantity', '>', 0)->count();
                            $capacity = $loc->capacity ?? 5000;
                            $capacityPct = $capacity > 0 ? min(100, round($totalQty / $capacity * 100)) : 0;
                            
                            // Modernizing styling based on state
                            $hasPendingRes = isset($pendingReservations[$loc->id]);
                            
                            $capBarColor = 'bg-emerald-400';
                            if ($capacityPct >= 100) $capBarColor = 'bg-rose-500';
                            elseif ($capacityPct >= 80) $capBarColor = 'bg-orange-400';
                            elseif ($capacityPct >= 50) $capBarColor = 'bg-yellow-400';

                            // Base card classes
                            $baseClasses = "relative group rounded-2xl p-4 shadow-sm transition-all duration-300 border flex flex-col items-center justify-between min-h-[11.5rem] h-full cursor-pointer overflow-hidden";
                            
                            if ($loc->status === 'inactive') {
                                $cardClasses = "$baseClasses bg-slate-50/50 border-slate-200 text-slate-400 grayscale filter";
                                $statusLabel = 'ปิดใช้งาน';
                                $indicatorColor = 'bg-rose-500 shadow-rose-500/50';
                                $mainBg = 'bg-slate-100';
                            } elseif ($loc->status === 'full') {
                                $cardClasses = "$baseClasses bg-white border-orange-200 hover:border-orange-300 hover:shadow-[0_8px_30px_rgba(249,115,22,0.15)] hover:-translate-y-1";
                                $statusLabel = 'เต็ม';
                                $indicatorColor = 'bg-orange-500 shadow-orange-500/50';
                                $mainBg = 'bg-orange-50 text-orange-900';
                            } elseif ($totalQty > 0) {
                                $cardClasses = "$baseClasses bg-white border-blue-200 hover:border-blue-300 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] hover:-translate-y-1";
                                $statusLabel = 'มีสินค้า';
                                $indicatorColor = 'bg-blue-500 shadow-blue-500/50';
                                $mainBg = 'bg-blue-50 text-blue-900';
                            } elseif ($totalReservedLoc > 0) {
                                $cardClasses = "$baseClasses bg-white border-yellow-200 hover:border-yellow-300 hover:shadow-[0_8px_30px_rgba(234,179,8,0.15)] hover:-translate-y-1";
                                $statusLabel = 'กันสต็อก';
                                $indicatorColor = 'bg-yellow-400 shadow-yellow-400/50';
                                $mainBg = 'bg-yellow-50 text-yellow-900';
                            } else {
                                $cardClasses = $hasPendingRes
                                    ? "$baseClasses bg-white border-purple-200 hover:border-purple-300 hover:shadow-[0_8px_30px_rgba(168,85,247,0.15)] hover:-translate-y-1"
                                    : "$baseClasses bg-white border-emerald-200 hover:border-emerald-300 hover:shadow-[0_8px_30px_rgba(16,185,129,0.15)] hover:-translate-y-1";
                                $statusLabel = $hasPendingRes ? 'จองรอเข้า' : 'ว่าง';
                                $indicatorColor = $hasPendingRes ? 'bg-purple-500 shadow-purple-500/50' : 'bg-emerald-400 shadow-emerald-400/50';
                                $mainBg = $hasPendingRes ? 'bg-purple-50 text-purple-900' : 'bg-emerald-50 text-emerald-900';
                            }
                        @endphp

                        @php $pendingRes = $pendingReservations[$loc->id] ?? null; @endphp

                        <!-- Location Card -->
                        <div onclick="openLocationPopup({{ $loc->id }}, '{{ addslashes($loc->name) }}')"
                            class="{{ $cardClasses }}">

                            {{-- Background subtle bloom --}}
                            <div class="absolute inset-0 {{ $mainBg }} opacity-10 blur-xl pointer-events-none group-hover:opacity-40 transition-opacity"></div>

                            {{-- Reservation tag (if any) --}}
                            @if($pendingRes)
                                <div class="absolute top-0 left-0 bg-purple-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-br-lg shadow-sm z-10 tracking-widest uppercase">
                                    จอง
                                </div>
                            @endif

                            {{-- Status Indicator --}}
                            <div class="absolute top-3 right-3 flex gap-1 z-10">
                                <span class="w-2.5 h-2.5 rounded-full {{ $indicatorColor }} shadow-sm"></span>
                            </div>

                            {{-- Name/Zone --}}
                            <div class="text-center w-full z-10 flex-col flex items-center justify-center pt-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400/80 mb-0.5 w-full line-clamp-2 leading-tight px-1 break-words" title="{{ $loc->name }}">{{ $loc->name }}</span>
                                <div class="text-4xl font-black tracking-tighter text-slate-800">{{ $loc->shelf }}</div>
                                <span class="text-[11px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100 mt-1">B-{{ $loc->bin }}</span>
                            </div>

                            {{-- Content/Capacity --}}
                            <div class="w-full text-center mt-auto z-10 bg-white/60 p-1.5 rounded-xl border border-white">
                                @if($loc->status === 'inactive')
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ปิด</span>
                                @else
                                    {{-- Progress Bar --}}
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1 overflow-hidden shadow-inner">
                                        <div class="{{ $capBarColor }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $capacityPct }}%"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] font-bold px-1 text-slate-500">
                                        <span class="text-slate-800">{{ number_format($totalQty) }}</span>
                                        <span>{{ number_format($capacity) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Floating Hover Tooltip --}}
                            <div class="absolute z-50 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 bottom-full mb-2 left-1/2 transform -translate-x-1/2 w-48 bg-slate-900/95 backdrop-blur-xl text-white p-3.5 rounded-2xl shadow-2xl text-sm pointer-events-none scale-95 group-hover:scale-100 origin-bottom border border-slate-700/50">
                                <div class="font-black text-sm uppercase tracking-widest mb-2 border-b border-slate-700 pb-2 text-center text-slate-200 flex justify-center gap-1.5 items-center">
                                    <span class="w-2 h-2 rounded-full {{ $indicatorColor }}"></span>
                                    {{ $loc->name }}
                                </div>
                                <div class="space-y-1.5 text-xs font-medium">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400">สินค้า:</span> 
                                        <span class="font-bold text-blue-300 bg-blue-900/40 px-1.5 rounded">{{ number_format($totalQty) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400">จองไว้:</span> 
                                        <span class="font-bold text-yellow-300 bg-yellow-900/40 px-1.5 rounded">{{ number_format($totalReservedLoc) }}</span>
                                    </div>
                                    @if($pendingRes)
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400">รอเข้า:</span> 
                                        <span class="font-bold text-purple-300 bg-purple-900/40 px-1.5 rounded">+{{ number_format($pendingRes->expected_qty) }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between items-center pt-2 mt-2 border-t border-slate-700/50">
                                        <span class="text-slate-400">พร้อมเบิก:</span> 
                                        <span class="font-bold text-emerald-300 bg-emerald-900/40 px-1.5 rounded">{{ number_format($availableLoc) }}</span>
                                    </div>
                                </div>
                                <svg class="absolute text-slate-900/95 h-3 w-full left-0 top-full" viewBox="0 0 255 255"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- Modern Glass Modal --}}
    <div id="locationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        {{-- Content --}}
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col relative z-10 transform scale-95 transition-transform duration-300" id="modalContent">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-blue-50 to-transparent -z-10 rounded-tr-[2rem] pointer-events-none"></div>

            <div class="p-6 sm:p-8 border-b border-slate-100 flex justify-between items-center bg-white/50 rounded-t-[2rem]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-blue-100">📍</div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">LOCATION DETAILS</h3>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight" id="modalTitleName">...</h2>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors shadow-sm border border-slate-200 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 sm:p-8 overflow-y-auto bg-slate-50/30 flex-1 relative">
                
                {{-- Summary inside modal --}}
                <div id="modalSummary" class="hidden grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">สินค้าทั้งหมด</p>
                        <p id="modalTotalQty" class="text-2xl font-black text-blue-600">0</p>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center relative overflow-hidden">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">กันสต็อก</p>
                        <p id="modalTotalReserved" class="text-2xl font-black text-yellow-600">0</p>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">พร้อมเบิก</p>
                        <p id="modalTotalAvailable" class="text-2xl font-black text-emerald-600">0</p>
                    </div>
                </div>

                <div id="loadingIndicator" class="text-center py-16 hidden">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4 animate-spin shadow-sm">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-widest animate-pulse">กำลังโหลดข้อมูล...</p>
                </div>

                <div id="emptyMessage" class="text-center py-16 hidden relative z-10">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border border-slate-200">🪹</div>
                    <h4 class="text-xl font-black text-slate-700 tracking-tight">ไม่มีสินค้าในพื้นที่นี้</h4>
                    <p class="text-slate-400 text-sm mt-2 font-medium">ตำแหน่งนี้ว่างและพร้อมใช้งานสำหรับการรับสินค้าใหม่</p>
                </div>

                <div id="itemsTableContainer" class="hidden bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center w-16">#</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">SKU / ชื่อสินค้า</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center whitespace-nowrap">ข้อมูล Lot</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">รวม</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">กันไว้</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">พร้อมใช้</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody" class="divide-y divide-slate-100">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-white rounded-b-[2rem] flex justify-end">
                <button onclick="closeModal()" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 font-bold py-2.5 px-8 rounded-xl shadow-sm transition-all focus:outline-none">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Custom modal transition classes */
        #locationModal.show {
            opacity: 1;
        }
        #locationModal.show #modalContent {
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
        }
    </style>

    <script>
        function openLocationPopup(locationId, locationName) {
            const modal = document.getElementById('locationModal');
            modal.classList.remove('hidden');
            // Timeout to allow display:block to apply before adding opacity
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            
            document.getElementById('modalTitleName').innerText = locationName;

            document.getElementById('itemsTableContainer').classList.add('hidden');
            document.getElementById('emptyMessage').classList.add('hidden');
            document.getElementById('modalSummary').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('itemsTableBody').innerHTML = '';

            fetch(`/api/locations/${locationId}/items`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    if (data.items.length === 0) {
                        document.getElementById('emptyMessage').classList.remove('hidden');
                    } else {
                        // Calculate totals
                        let totalQty = 0, totalReserved = 0, totalAvailable = 0;
                        data.items.forEach(item => {
                            totalQty += item.quantity;
                            totalReserved += item.reserved;
                            totalAvailable += item.available;
                        });

                        // Show summary
                        document.getElementById('modalTotalQty').innerText = totalQty.toLocaleString();
                        document.getElementById('modalTotalReserved').innerText = totalReserved.toLocaleString();
                        document.getElementById('modalTotalAvailable').innerText = totalAvailable.toLocaleString();
                        document.getElementById('modalSummary').classList.remove('hidden');

                        // Render table
                        document.getElementById('itemsTableContainer').classList.remove('hidden');
                        let html = '';
                        data.items.forEach((item, index) => {
                            const reservedBadge = item.reserved > 0
                                ? `<span class="bg-yellow-50 text-yellow-700 font-bold px-2.5 py-1 rounded-lg border border-yellow-200 inline-block">${item.reserved}</span>`
                                : `<span class="text-slate-300 font-medium">-</span>`;

                            html += `
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-sm font-bold text-slate-300">${index + 1}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">${item.product_name}</div>
                                        <div class="text-xs font-mono text-slate-500 mt-1">${item.sku}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs">
                                        <div class="font-bold text-slate-700 bg-slate-100 inline-block px-2 py-0.5 rounded border border-slate-200">${item.lot_number || '-'}</div>
                                        <div class="text-slate-400 mt-1.5">${item.received_date}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200 inline-block">${item.quantity}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">${reservedBadge}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1 rounded-lg border border-emerald-200 inline-block">${item.available}</span>
                                    </td>
                                </tr>
                            `;
                        });
                        document.getElementById('itemsTableBody').innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    alert('ไม่สามารถดึงข้อมูลได้ โปรดลองอีกครั้ง');
                });
        }

        function closeModal() {
            const modal = document.getElementById('locationModal');
            modal.classList.remove('show');
            // Wait for transition to complete before hiding
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const modal = document.getElementById('locationModal');
                if (!modal.classList.contains('hidden')) {
                    closeModal();
                }
            }
        });
    </script>
</x-app-layout>