<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 รายการสินค้าคงคลัง (Inventory List)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            @php
                $totalProducts = $products->count();
                $totalQty = $products->sum('stocks_sum_quantity');
                $totalReserved = $products->sum('stocks_sum_reserved_qty');
                $totalTransit = $products->sum('transit_quantity');
                $totalAvailable = $totalQty - $totalReserved;
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-indigo-500">
                    <p class="text-xs text-gray-500 font-bold">📋 รายการสินค้า</p>
                    <p class="text-2xl font-black text-indigo-600">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 font-bold">📦 สต็อกในคลัง</p>
                    <p class="text-2xl font-black text-blue-600">{{ number_format($totalQty) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                    <p class="text-xs text-gray-500 font-bold">🔒 ถูกจอง</p>
                    <p class="text-2xl font-black text-yellow-600">{{ number_format($totalReserved) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-500">
                    <p class="text-xs text-gray-500 font-bold">🚚 ระหว่างทาง</p>
                    <p class="text-2xl font-black text-orange-500">{{ number_format($totalTransit) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 font-bold">✅ พร้อมจ่าย</p>
                    <p class="text-2xl font-black text-green-600">{{ number_format($totalAvailable) }}</p>
                </div>
            </div>

            {{-- Product Cards --}}
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
            <div class="bg-white rounded-2xl shadow-md mb-4 overflow-hidden border border-gray-200">
                {{-- Product Header Row --}}
                <div class="p-4 flex flex-wrap items-center justify-between gap-4 cursor-pointer hover:bg-gray-50 transition"
                     onclick="toggleDetail('detail-{{ $product->id }}')">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            @if($product->qr_code_image)
                                <img id="qrcode-img-{{ $product->id }}"
                                     src="{{ asset('storage/qrcodes/' . $product->qr_code_image) }}"
                                     class="w-12 h-12 rounded border" alt="QR">
                            @else
                                <div class="w-12 h-12 rounded border bg-gray-100 flex items-center justify-center text-gray-400 text-xs">No QR</div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-800 text-lg truncate">{{ $product->name }}</h3>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="font-mono">{{ $product->sku }}</span>
                                <span>|</span>
                                <span class="font-mono">{{ $product->barcode }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Badges --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="text-center px-3">
                            <p class="text-xs text-gray-400">ในคลัง</p>
                            <p class="text-lg font-black text-blue-600">{{ number_format($qty) }}</p>
                        </div>
                        <div class="text-center px-3">
                            <p class="text-xs text-gray-400">จอง</p>
                            <p class="text-lg font-black text-yellow-600">{{ number_format($reserved) }}</p>
                        </div>
                        @if($transit > 0)
                        <div class="text-center px-3">
                            <p class="text-xs text-gray-400">ระหว่างทาง</p>
                            <p class="text-lg font-black text-orange-500">🚚 {{ number_format($transit) }}</p>
                        </div>
                        @endif
                        <div class="text-center px-3 border-l border-gray-200 pl-3">
                            <p class="text-xs text-gray-400">พร้อมจ่าย</p>
                            <p class="text-lg font-black {{ $available > 0 ? 'text-green-600' : 'text-red-500' }}">{{ number_format($available) }}</p>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex gap-2 ml-2">
                            @if(auth()->user()->role === 'admin')
                            <button onclick="event.stopPropagation(); openReservationModal('{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $qty }}, {{ $reserved }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white py-1.5 px-3 rounded-lg text-xs font-bold shadow-sm transition">
                                🔒 จอง
                            </button>
                            @endif
                            <button onclick="event.stopPropagation(); printStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')"
                                class="bg-gray-700 hover:bg-black text-white py-1.5 px-3 rounded-lg text-xs font-bold shadow-sm transition">
                                🖨️
                            </button>
                            <button onclick="event.stopPropagation(); printQrStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')"
                                class="bg-indigo-600 hover:bg-indigo-800 text-white py-1.5 px-3 rounded-lg text-xs font-bold shadow-sm transition">
                                📱
                            </button>
                        </div>

                        {{-- Expand arrow --}}
                        <svg id="arrow-{{ $product->id }}" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Expandable Detail Section --}}
                <div id="detail-{{ $product->id }}" class="hidden border-t border-gray-200 bg-gray-50">
                    @if($storageStocks->isEmpty() && $transitStocks->isEmpty())
                        <div class="p-6 text-center text-gray-400">
                            <span class="text-3xl block mb-2">🪹</span>
                            ไม่มีสต็อกของสินค้านี้ในระบบ
                        </div>
                    @else
                        <div class="p-4">
                            {{-- Storage stocks grouped by location --}}
                            @if($locationGroups->isNotEmpty())
                            <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">📍 แยกตามตำแหน่งจัดเก็บ</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                                @foreach($locationGroups as $locationName => $stocks)
                                @php
                                    $locQty = $stocks->sum('quantity');
                                    $locReserved = $stocks->sum('reserved_qty');
                                    $locAvailable = $locQty - $locReserved;
                                    $location = $stocks->first()->location;
                                @endphp
                                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="font-bold text-gray-800">📍 {{ $locationName }}</span>
                                            @if($location)
                                            <span class="text-xs text-gray-400 ml-1">Zone: {{ $location->zone }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold">{{ $stocks->count() }} lot</span>
                                    </div>
                                    {{-- Stats --}}
                                    <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3">
                                        <div class="bg-blue-50 rounded-lg p-2">
                                            <p class="text-gray-400">จำนวน</p>
                                            <p class="font-black text-blue-600 text-base">{{ number_format($locQty) }}</p>
                                        </div>
                                        <div class="bg-yellow-50 rounded-lg p-2">
                                            <p class="text-gray-400">จอง</p>
                                            <p class="font-black text-yellow-600 text-base">{{ number_format($locReserved) }}</p>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-2">
                                            <p class="text-gray-400">พร้อมจ่าย</p>
                                            <p class="font-black text-green-600 text-base">{{ number_format($locAvailable) }}</p>
                                        </div>
                                    </div>
                                    {{-- Lot details --}}
                                    <div class="space-y-1">
                                        @foreach($stocks as $stock)
                                        <div class="flex items-center justify-between text-xs bg-gray-50 rounded-lg px-3 py-1.5">
                                            <div class="text-gray-500">
                                                <span class="font-mono">{{ $stock->lot_number ?? '-' }}</span>
                                                <span class="text-gray-300 mx-1">|</span>
                                                <span>{{ $stock->received_date ? date('d/m/Y', strtotime($stock->received_date)) : '-' }}</span>
                                            </div>
                                            <div class="flex gap-3">
                                                <span class="text-blue-600 font-bold">{{ $stock->quantity }}</span>
                                                @if($stock->reserved_qty > 0)
                                                <span class="text-yellow-600 font-bold">(จอง {{ $stock->reserved_qty }})</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Transit stocks --}}
                            @if($transitStocks->isNotEmpty())
                            <h4 class="text-sm font-bold text-orange-500 uppercase mb-3">🚚 ระหว่างทาง (Transit)</h4>
                            <div class="bg-orange-50 rounded-xl border border-orange-200 p-4">
                                @foreach($transitStocks as $ts)
                                <div class="flex items-center justify-between text-sm py-1.5">
                                    <div class="text-gray-600">
                                        <span class="font-mono text-xs">{{ $ts->lot_number ?? '-' }}</span>
                                        <span class="text-gray-300 mx-1">|</span>
                                        <span class="text-xs">{{ $ts->received_date ? date('d/m/Y', strtotime($ts->received_date)) : '-' }}</span>
                                    </div>
                                    <span class="font-bold text-orange-600">🚚 {{ number_format($ts->quantity) }} ชิ้น</span>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Delete button (admin only) --}}
                            @if(auth()->user()->role === 'admin')
                            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end">
                                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสินค้า {{ addslashes($product->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold transition">
                                        🗑️ ลบสินค้านี้
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- hidden barcode img for print --}}
                @if($product->barcode_image)
                <img id="barcode-img-{{ $product->id }}" src="{{ asset('storage/barcodes/' . $product->barcode_image) }}" style="display:none" alt="barcode">
                @endif
            </div>
            @endforeach

        </div>
    </div>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex justify-center items-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-1/3 p-6 transform transition-all">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-800">จัดการการจอง: <span id="resModalName" class="text-blue-600">...</span></h3>
                <button onclick="closeReservationModal()" class="text-gray-400 hover:text-red-500 font-bold text-2xl leading-none">&times;</button>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg border flex justify-between text-sm">
                    <div>📦 มีทั้งหมด: <span id="resModalTotal" class="font-bold text-blue-600">0</span></div>
                    <div>🔒 จองแล้ว: <span id="resModalReserved" class="font-bold text-yellow-600">0</span></div>
                    <div>✅ ว่าง: <span id="resModalAvailable" class="font-bold text-green-600">0</span></div>
                </div>

                <div class="flex border-b">
                    <button id="tabReserve" onclick="switchTab('reserve')" class="flex-1 py-2 font-bold text-yellow-600 border-b-2 border-yellow-500 bg-yellow-50">🔒 จองสินค้า (Reserve)</button>
                    <button id="tabRelease" onclick="switchTab('release')" class="flex-1 py-2 font-bold text-gray-500 hover:text-green-600">🔓 ปลดจอง (Release)</button>
                </div>

                <form id="reservationForm" method="POST" action="{{ route('reservation.reserve') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="resProductId">

                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">📍 ตำแหน่งที่จะจอง:</label>
                        <select name="location_id" id="resLocationSelect"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">ทุกตำแหน่ง (FIFO อัตโนมัติ)</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวน (Qty):</label>
                        <input type="number" name="quantity" min="1" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" onclick="closeReservationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            ยกเลิก
                        </button>
                        <button type="submit" id="resSubmitBtn" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            ยืนยันการจอง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        function toggleDetail(id) {
            const el = document.getElementById(id);
            const productId = id.replace('detail-', '');
            const arrow = document.getElementById('arrow-' + productId);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                el.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        function openReservationModal(id, name, total, reserved) {
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

            if (type === 'reserve') {
                form.action = "{{ route('reservation.reserve') }}";
                btn.innerText = "ยืนยันการจอง";
                btn.className = "bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded";
                tabReserve.className = "flex-1 py-2 font-bold text-yellow-600 border-b-2 border-yellow-500 bg-yellow-50";
                tabRelease.className = "flex-1 py-2 font-bold text-gray-500 hover:text-green-600";
            } else {
                form.action = "{{ route('reservation.release') }}";
                btn.innerText = "ยืนยันการปลดจอง";
                btn.className = "bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded";
                tabReserve.className = "flex-1 py-2 font-bold text-gray-500 hover:text-yellow-600";
                tabRelease.className = "flex-1 py-2 font-bold text-green-600 border-b-2 border-green-500 bg-green-50";
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
    </script>
</x-app-layout>