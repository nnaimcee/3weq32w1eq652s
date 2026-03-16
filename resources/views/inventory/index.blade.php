<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            สต็อกสินค้า (Inventory)
        </h2>
    </x-slot>

    <div class="py-6 w-full relative z-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Top KPIs --}}
            @php
                $totalProducts = $products->count();
                $totalQty = $products->sum('stocks_sum_quantity');
                $totalReserved = $products->sum('stocks_sum_reserved_qty');
                $totalTransit = $products->sum('transit_quantity');
                $totalAvailable = $totalQty - $totalReserved;
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300"></span> รายการสินค้า</p>
                    <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-400"></span> สต็อกรวม</p>
                    <p class="text-3xl font-black text-blue-600 tracking-tight">{{ number_format($totalQty) }}</p>
                </div>
                <div class="bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> ถูกจอง</p>
                    <p class="text-3xl font-black text-amber-500 tracking-tight">{{ number_format($totalReserved) }}</p>
                </div>
                <div class="bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span> ระหว่างทาง</p>
                    <p class="text-3xl font-black text-rose-500 tracking-tight">{{ number_format($totalTransit) }}</p>
                </div>
                <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute right-0 top-0 bottom-0 w-32 bg-white/10 rounded-full blur-[30px] -z-10 group-hover:scale-150 transition-transform duration-700"></div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-100 uppercase tracking-widest mb-1">พร้อมจ่าย</p>
                        <p class="text-3xl font-black text-white tracking-tight">{{ number_format($totalAvailable) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-xl shadow-sm border border-white/10">✅</div>
                </div>
            </div>

            {{-- Main List Section --}}
            <div class="bg-white rounded-[2rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden flex flex-col min-h-[500px]">
                
                {{-- Toolbar (Search & Actions) --}}
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="relative w-full sm:w-96 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="searchInput" name="search" value="{{ request('search') }}" onkeyup="filterProducts()" placeholder="ค้นหาสินค้า (ชื่อ, SKU, Barcode)..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200/50 text-sm transition-all focus:outline-none">
                    </div>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors text-sm w-full sm:w-auto">
                        <span class="text-lg leading-none">+</span> เพิ่มสินค้า
                    </a>
                    @endif
                </div>

                {{-- Table Data Grid --}}
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-white text-[10px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4 w-16 text-center">QR</th>
                                <th class="px-6 py-4">ข้อมูลสินค้า</th>
                                <th class="px-6 py-4 text-right">ในคลัง</th>
                                <th class="px-6 py-4 text-right">จอง</th>
                                <th class="px-6 py-4 text-right">ระหว่างทาง</th>
                                <th class="px-6 py-4 text-right">พร้อมจ่าย</th>
                                <th class="px-6 py-4 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm" id="productTableBody">
                            @forelse ($products as $product)
                            @php
                                $qty = $product->stocks_sum_quantity ?? 0;
                                $reserved = $product->stocks_sum_reserved_qty ?? 0;
                                $transit = $product->transit_quantity ?? 0;
                                $available = $qty - $reserved;
                            @endphp
                            <tr class="product-row hover:bg-slate-50 cursor-pointer transition-colors group" data-search="{{ mb_strtolower($product->sku . ' ' . $product->name . ' ' . $product->barcode) }}" onclick="openPanel('{{ $product->id }}')">
                                <td class="px-6 py-4 text-center">
                                    @if($product->qr_code_image)
                                        <img src="{{ $product->qr_code_image }}" class="w-10 h-10 rounded-lg border border-slate-200 object-cover shadow-sm" alt="QR" id="qrcode-img-{{ $product->id }}">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[8px] text-slate-400 font-bold shadow-inner">NO QR</div>
                                    @endif
                                    @if($product->barcode_image)
                                        <img id="barcode-img-{{ $product->id }}" src="{{ $product->barcode_image }}" style="display:none" alt="barcode">
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-base group-hover:text-indigo-600 transition-colors">{{ $product->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-mono font-bold text-slate-600 bg-white border border-slate-200 px-1.5 py-0.5 rounded shadow-sm">{{ $product->sku }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $product->barcode }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-black text-blue-600 text-lg">{{ number_format($qty) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-black text-amber-500 text-lg">{{ number_format($reserved) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-black text-rose-500 text-lg">{{ $transit > 0 ? number_format($transit) : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-black {{ $available > 0 ? 'text-emerald-600' : 'text-slate-300' }} text-lg">{{ $available > 0 ? number_format($available) : '0' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-300 group-hover:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </td>
                            </tr>
                            @empty
                            <tr id="emptyRow">
                                <td colspan="7" class="p-12 text-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100"><span class="text-2xl">📦</span></div>
                                    <p class="font-bold text-slate-600">ไม่มีรายการสินค้า</p>
                                    <p class="text-sm mt-1">ลองเพิ่มสินค้าเข้าสู่ระบบ</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Offcanvas Overlay --}}
    <div id="slideOverOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[60] opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeAllPanels()"></div>

    {{-- Slide-Out Panels for each product --}}
    @foreach ($products as $product)
    @php
        $qty = $product->stocks_sum_quantity ?? 0;
        $reserved = $product->stocks_sum_reserved_qty ?? 0;
        $transit = $product->transit_quantity ?? 0;
        $available = $qty - $reserved;
        $storageStocks = $product->stocks->filter(fn($s) => !$transitLocationIds->contains($s->location_id));
        $transitStocks = $product->stocks->filter(fn($s) => $transitLocationIds->contains($s->location_id));
        $locationGroups = $storageStocks->groupBy(fn($s) => $s->location ? $s->location->name : 'ไม่ทราบ');
    @endphp
    <div id="panel-{{ $product->id }}" class="fixed inset-y-0 right-0 z-[70] w-full max-w-md bg-slate-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col pointer-events-none">
        
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 bg-white flex items-start justify-between sticky top-0 z-10">
            <div class="flex gap-4 items-center">
                @if($product->qr_code_image)
                    <img src="{{ $product->qr_code_image }}" class="w-12 h-12 rounded-xl border border-slate-200 shadow-sm" alt="QR">
                @endif
                <div>
                    <h3 class="font-bold text-lg text-slate-800 leading-tight">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $product->sku }}</span>
                    </div>
                </div>
            </div>
            <button onclick="closePanel('{{ $product->id }}')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            
            {{-- Quick Actions --}}
            <div class="grid grid-cols-2 gap-3">
                <button onclick="printQrStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')" class="flex flex-col items-center justify-center p-3 bg-white border border-indigo-100 hover:border-indigo-300 hover:shadow-md rounded-2xl transition-all group">
                    <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">📱</span>
                    <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-widest">พิมพ์ QR</span>
                </button>
                <button onclick="printStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')" class="flex flex-col items-center justify-center p-3 bg-white border border-slate-200 hover:border-slate-300 hover:shadow-md rounded-2xl transition-all group">
                    <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">🖨️</span>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">พิมพ์ Barcode</span>
                </button>
                @if(auth()->user()->role === 'admin')
                <button onclick="openReservationModal('{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $qty }}, {{ $reserved }})" class="col-span-2 flex flex-row items-center justify-center gap-2 p-3 bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-white shadow-md rounded-2xl transition-all font-bold text-sm">
                    🔒 จัดการการจอง (Reserve)
                </button>
                @endif
            </div>

            {{-- Summary Stats Mini --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-4 grid grid-cols-3 divide-x divide-slate-100 shadow-sm">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">รวมทั้งหมด</p>
                    <p class="font-black text-blue-600 text-lg">{{ number_format($qty) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">จองแล้ว</p>
                    <p class="font-black text-amber-500 text-lg">{{ number_format($reserved) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">พร้อมจ่าย</p>
                    <p class="font-black text-emerald-600 text-lg">{{ number_format($available) }}</p>
                </div>
            </div>

            {{-- Locations --}}
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">📍 ตำแหน่งจัดเก็บ</h4>
                @if($locationGroups->isEmpty())
                    <div class="bg-white rounded-xl border border-slate-100 p-6 text-center text-slate-400 text-sm">ไม่มีสต็อกในคลัง</div>
                @else
                    <div class="space-y-3">
                        @foreach($locationGroups as $locationName => $stocks)
                        @php
                            $locQty = $stocks->sum('quantity');
                            $locReserved = $stocks->sum('reserved_qty');
                            $locAvailable = $locQty - $locReserved;
                            $location = $stocks->first()->location;
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-3 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                <p class="font-bold text-slate-800 text-sm flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-400"></span> {{ $locationName }}</p>
                                <p class="font-black text-blue-600">{{ number_format($locQty) }} <span class="text-xs font-semibold text-slate-400">ชิ้น</span></p>
                            </div>
                            <div class="p-3 bg-white space-y-2">
                                @foreach($stocks as $stock)
                                <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-50 last:border-0 last:pb-0">
                                    <div class="flex flex-col">
                                        <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1 rounded">{{ $stock->lot_number ?? 'No Lot' }}</span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">{{ $stock->received_date ? date('d/m/y', strtotime($stock->received_date)) : '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($stock->reserved_qty > 0)
                                            <span class="text-[10px] bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded font-bold border border-amber-100">จอง {{ $stock->reserved_qty }}</span>
                                        @endif
                                        <span class="font-bold text-slate-800 text-sm">{{ $stock->quantity }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($transitStocks->isNotEmpty())
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">🚚 ระหว่างทาง</h4>
                <div class="bg-white rounded-2xl border border-orange-200 overflow-hidden shadow-sm">
                    @foreach($transitStocks as $ts)
                    <div class="flex items-center justify-between p-3 border-b border-orange-50 last:border-0">
                        <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-1.5 rounded">{{ $ts->lot_number ?? '-' }}</span>
                        <span class="font-black text-orange-600 text-sm flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span> {{ number_format($ts->quantity) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(auth()->user()->role === 'admin')
            <div class="pt-6 border-t border-slate-200 mt-auto">
                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                    onsubmit="return confirm('คุณแน่ใจใช่ไหมที่จะลบสินค้า {{ addslashes($product->name) }} ถาวร?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 font-bold rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        ลบสินค้านี้ออกจากระบบ
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
    @endforeach

    <!-- Reservation Modal -->
    <div id="reservationModal" class="hidden fixed inset-0 bg-slate-900/60 z-[80] flex justify-center items-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl shadow-2xl w-11/12 md:w-1/3 p-6 transform transition-all border border-slate-200/50">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">🔒</span> จัดการการจอง</h3>
                <button onclick="closeReservationModal()" class="w-8 h-8 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-500 rounded-full flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="space-y-5">
                <div>
                     <p class="text-sm font-bold text-indigo-600 mb-3 bg-indigo-50 px-3 py-2 rounded-lg border border-indigo-100" id="resModalName">...</p>
                </div>
               
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 grid grid-cols-3 divide-x divide-slate-200">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">มีทั้งหมด</p>
                        <p class="font-black text-blue-600 text-lg" id="resModalTotal">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">จองแล้ว</p>
                        <p class="font-black text-amber-500 text-lg" id="resModalReserved">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ว่าง</p>
                        <p class="font-black text-emerald-600 text-lg" id="resModalAvailable">0</p>
                    </div>
                </div>

                <div class="flex p-1 bg-slate-100 rounded-xl relative">
                    <div id="tabIndicator" class="absolute w-1/2 h-full bg-white rounded-lg shadow-sm border border-slate-200/50 transition-transform duration-300 left-0 top-0"></div>
                    <button id="tabReserve" onclick="switchTab('reserve')" class="flex-1 py-2 text-sm font-bold text-amber-600 relative z-10">จองสินค้า</button>
                    <button id="tabRelease" onclick="switchTab('release')" class="flex-1 py-2 text-sm font-bold text-slate-500 relative z-10 transition-colors">ปลดจอง</button>
                </div>

                <form id="reservationForm" method="POST" action="{{ route('reservation.reserve') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="resProductId">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">ตำแหน่งที่จะจอง</label>
                            <select name="location_id" id="resLocationSelect" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 focus:bg-white transition-all outline-none">
                                <option value="">ทุกตำแหน่ง (FIFO อัตโนมัติ)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">จำนวน (ชิ้น)</label>
                            <input type="number" name="quantity" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-lg font-bold focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 focus:bg-white transition-all outline-none">
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="resSubmitBtn" class="w-full bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-transform hover:-translate-y-0.5">
                            ยืนยันการจอง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Offcanvas Logic
        let activePanelId = null;

        function openPanel(productId) {
            closeAllPanels();
            const panelId = 'panel-' + productId;
            const panel = document.getElementById(panelId);
            const overlay = document.getElementById('slideOverOverlay');

            if (panel) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                panel.classList.remove('translate-x-full', 'pointer-events-none');
                panel.classList.add('pointer-events-auto');
                activePanelId = panelId;
            }
        }

        function closePanel(productId) {
            const panelId = 'panel-' + productId;
            const panel = document.getElementById(panelId);
            const overlay = document.getElementById('slideOverOverlay');

            if (panel) {
                panel.classList.add('translate-x-full', 'pointer-events-none');
                panel.classList.remove('pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                activePanelId = null;
            }
        }

        function closeAllPanels() {
            if (activePanelId) {
                closePanel(activePanelId.replace('panel-', ''));
            }
        }


        // Product → location stock data for location dropdown
        const productLocations = {!! json_encode(
            $products->mapWithKeys(function($p) use ($transitLocationIds) {
                $locs = $p->stocks
                    ->filter(fn($s) => !$transitLocationIds->contains($s->location_id) && $s->quantity > 0)
                    ->groupBy('location_id')
                    ->map(function($stocks) {
                        $loc = $stocks->first()->location;
                        return [
                            'id' => $stocks->first()->location_id,
                            'name' => $loc ? $loc->name : '?',
                            'zone' => $loc ? $loc->zone : '',
                            'qty' => $stocks->sum('quantity'),
                            'reserved' => $stocks->sum('reserved_qty'),
                            'available' => $stocks->sum('quantity') - $stocks->sum('reserved_qty'),
                        ];
                    })->values();
                return [$p->id => $locs];
            })
        ) !!};

        function openReservationModal(id, name, total, reserved) {
            closeAllPanels(); // Close the sliding panel if open
            document.getElementById('reservationModal').classList.remove('hidden');
            document.getElementById('resProductId').value = id;
            document.getElementById('resModalName').innerText = name;
            document.getElementById('resModalTotal').innerText = total;
            document.getElementById('resModalReserved').innerText = reserved;
            document.getElementById('resModalAvailable').innerText = total - reserved;

            // Populate location dropdown
            const select = document.getElementById('resLocationSelect');
            select.innerHTML = '<option value="">ทุกตำแหน่ง (FIFO อัตโนมัติ)</option>';
            const locs = productLocations[id] || [];
            locs.forEach(loc => {
                if (loc.available > 0) {
                    const opt = document.createElement('option');
                    opt.value = loc.id;
                    opt.textContent = `📍 ${loc.name} (${loc.zone}) — ว่าง ${loc.available} ชิ้น`;
                    select.appendChild(opt);
                }
            });

            switchTab('reserve');
        }

        function closeReservationModal() {
            document.getElementById('reservationModal').classList.add('hidden');
        }

        function switchTab(type) {
            const form = document.getElementById('reservationForm');
            const btn = document.getElementById('resSubmitBtn');
            const tabReserve = document.getElementById('tabReserve');
            const tabRelease = document.getElementById('tabRelease');
            const indicator = document.getElementById('tabIndicator');

            if (type === 'reserve') {
                form.action = "{{ route('reservation.reserve') }}";
                btn.innerText = "ยืนยันการจอง";
                btn.className = "w-full bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-transform hover:-translate-y-0.5";
                
                tabReserve.classList.replace('text-slate-500', 'text-amber-600');
                tabRelease.classList.replace('text-emerald-600', 'text-slate-500');
                indicator.style.transform = 'translateX(0%)';
            } else {
                form.action = "{{ route('reservation.release') }}";
                btn.innerText = "ยืนยันการปลดจอง";
                btn.className = "w-full bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-500 hover:to-teal-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-transform hover:-translate-y-0.5";
                
                tabRelease.classList.replace('text-slate-500', 'text-emerald-600');
                tabReserve.classList.replace('text-amber-600', 'text-slate-500');
                indicator.style.transform = 'translateX(100%)';
            }
        }

        function printStickerDirect(name, sku, productId) {
            const imgElement = document.getElementById(`barcode-img-${productId}`);
            if (!imgElement) { alert("❌ ไม่พบรูปภาพบาร์โค้ด"); return; }
            const imgSrc = imgElement.src;
            const printContent = `
                <div style="width: 50mm; height: 30mm; background: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-sizing: border-box; padding: 5px;">
                    <div style="font-size: 14px; font-weight: 600; font-family: sans-serif; margin-bottom: 2px; white-space: nowrap; overflow: hidden; width: 100%; color: black;">${name}</div>
                    <img src="${imgSrc}" style="height: 33px; width: auto; margin-bottom: 2px;">
                    <div style="font-size: 12px; font-family: monospace; font-weight: 500; color: black;">${sku}</div>
                </div>`;
            document.body.innerHTML = `<style>* { margin: 0; padding: 0; box-sizing: border-box; -webkit-print-color-adjust: exact; } @page { size: 50mm 30mm; margin: 0; } body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: white; }</style>${printContent}`;
            setTimeout(() => { window.print(); window.location.reload(); }, 200);
        }

        function printQrStickerDirect(name, sku, productId) {
            const imgElement = document.getElementById(`qrcode-img-${productId}`);
            if (!imgElement) { alert("❌ ไม่พบรูปภาพ QR Code"); return; }
            const imgSrc = imgElement.src;
            const printContent = `
                <div style="width: 50mm; height: 50mm; background: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-sizing: border-box; padding: 5px;">
                    <div style="font-size: 14px; font-weight: 600; font-family: sans-serif; margin-bottom: 4px; white-space: nowrap; overflow: hidden; width: 100%; color: black;">${name}</div>
                    <img src="${imgSrc}" style="width: 30mm; height: 30mm; margin-bottom: 4px; image-rendering: pixelated;">
                    <div style="font-size: 12px; font-family: monospace; font-weight: 500; color: black;">${sku}</div>
                </div>`;
            document.body.innerHTML = `<style>* { margin: 0; padding: 0; box-sizing: border-box; -webkit-print-color-adjust: exact; } @page { size: 50mm 50mm; margin: 0; } body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: white; }</style>${printContent}`;
            setTimeout(() => { window.print(); window.location.reload(); }, 200);
        }

        // Close modal on outside click
        document.getElementById('reservationModal').addEventListener('click', function(e) {
            if (e.target === this) closeReservationModal();
        });

        // Dynamic Filtering
        function filterProducts() {
            let filter = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('.product-row');
            let hasVisible = false;

            rows.forEach(row => {
                let searchableText = row.getAttribute('data-search');
                if (searchableText.includes(filter)) {
                    row.style.display = '';
                    hasVisible = true;
                } else {
                    row.style.display = 'none';
                }
            });

            let emptyRow = document.getElementById('emptyRow');
            if (emptyRow) {
                emptyRow.style.display = hasVisible || filter === '' ? 'none' : '';
                if (!hasVisible && filter !== '') {
                    emptyRow.innerHTML = `
                    <td colspan="7" class="p-12 text-center text-slate-400">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4"><span class="text-2xl">🔍</span></div>
                        <p class="font-bold text-slate-600 text-lg">ไม่พบสินค้า</p>
                        <p class="text-sm mt-1">ลองค้นหาด้วยคำอื่น</p>
                    </td>`;
                }
            }
        }
    </script>
</x-app-layout>