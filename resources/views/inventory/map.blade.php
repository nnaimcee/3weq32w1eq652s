<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            🗺️ แผนผังคลังสินค้า
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
                $totalReserved = $allLocations->sum(fn($l) => $l->stocks->sum('reserved_qty'));
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-gray-400">
                    <p class="text-xs text-gray-500 font-bold">📍 ตำแหน่งทั้งหมด</p>
                    <p class="text-2xl font-black text-gray-700">{{ $totalLocations }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-500">
                    <p class="text-xs text-gray-500 font-bold">📦 เต็มแล้ว</p>
                    <p class="text-2xl font-black text-orange-600">{{ $full }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 font-bold">📦 มีสินค้า</p>
                    <p class="text-2xl font-black text-blue-600">{{ $occupied }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 font-bold">🟢 ว่าง</p>
                    <p class="text-2xl font-black text-green-600">{{ $empty }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                    <p class="text-xs text-gray-500 font-bold">🏷️ จองแล้ว</p>
                    <p class="text-2xl font-black text-yellow-600">{{ number_format($totalReserved) }} ชิ้น</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-indigo-500">
                    <p class="text-xs text-gray-500 font-bold">📊 สินค้ารวม</p>
                    <p class="text-2xl font-black text-indigo-600">{{ number_format($totalItems) }} ชิ้น</p>
                </div>
            </div>

            {{-- Legend --}}
            <div class="bg-white p-4 rounded-xl shadow mb-6 flex flex-wrap justify-center gap-5 border border-gray-200">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium text-gray-600">ว่าง</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-blue-600"></span>
                    <span class="text-sm font-medium text-gray-600">มีสินค้า</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-yellow-500"></span>
                    <span class="text-sm font-medium text-gray-600">กันสต็อก</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-purple-500"></span>
                    <span class="text-sm font-medium text-gray-600">🔖 จองรอสินค้าเข้า</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-orange-500"></span>
                    <span class="text-sm font-medium text-gray-600">เต็ม</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-red-500"></span>
                    <span class="text-sm font-medium text-gray-600">ปิดใช้งาน</span>
                </div>
            </div>

            {{-- Zone Sections --}}
            @foreach($zones as $zoneName => $locations)
            @php
                $zoneQty = $locations->sum(fn($l) => $l->stocks->sum('quantity'));
                $zoneReserved = $locations->sum(fn($l) => $l->stocks->sum('reserved_qty'));
                $zoneOccupied = $locations->filter(fn($l) => $l->stocks->sum('quantity') > 0)->count();
            @endphp
            <div class="mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-600 w-2 h-8 rounded-full flex-shrink-0"></div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Zone: {{ $zoneName }}</h3>
                        <span class="text-sm text-gray-400">({{ $locations->count() }} ตำแหน่ง)</span>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500 pl-5 sm:pl-0">
                        <span class="bg-blue-50 px-2 py-1 rounded-lg">📦 {{ number_format($zoneQty) }} ชิ้น</span>
                        <span class="bg-yellow-50 px-2 py-1 rounded-lg">🏷️ จอง {{ number_format($zoneReserved) }}</span>
                        <span class="bg-green-50 px-2 py-1 rounded-lg">📍 ใช้งาน {{ $zoneOccupied }}/{{ $locations->count() }}</span>
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
                            $capBarColor = $capacityPct >= 100 ? 'bg-red-500' : ($capacityPct >= 80 ? 'bg-orange-400' : ($capacityPct >= 50 ? 'bg-yellow-400' : 'bg-green-500'));

                            if ($loc->status === 'inactive') {
                                $cardClasses = 'bg-red-50 border-red-300 text-red-900';
                                $statusLabel = 'ปิดใช้งาน';
                            } elseif ($loc->status === 'full') {
                                $cardClasses = 'bg-orange-50 border-orange-300 text-orange-900 hover:bg-orange-100 hover:shadow-xl hover:-translate-y-1';
                                $statusLabel = 'เต็ม';
                            } elseif ($totalQty > 0) {
                                $cardClasses = 'bg-blue-50 border-blue-300 text-blue-900 hover:bg-blue-100 hover:shadow-xl hover:-translate-y-1';
                                $statusLabel = 'มีสินค้า';
                            } elseif ($totalReservedLoc > 0) {
                                $cardClasses = 'bg-yellow-50 border-yellow-300 text-yellow-900 hover:bg-yellow-100 hover:shadow-xl hover:-translate-y-1';
                                $statusLabel = 'กันสต็อก';
                            } else {
                                // pending location reservation → purple, else green
                                $hasPendingRes = isset($pendingReservations[$loc->id]);
                                $cardClasses = $hasPendingRes
                                    ? 'bg-purple-50 border-purple-300 text-purple-900 hover:bg-purple-100 hover:shadow-xl hover:-translate-y-1'
                                    : 'bg-green-50 border-green-300 text-green-900 hover:bg-green-100 hover:shadow-xl hover:-translate-y-1';
                                $statusLabel = $hasPendingRes ? 'จองรอสินค้าเข้า' : 'ว่าง';
                            }
                        @endphp

                        @php $pendingRes = $pendingReservations[$loc->id] ?? null; @endphp

                        <div onclick="openLocationPopup({{ $loc->id }}, '{{ addslashes($loc->name) }}')"
                            class="relative group rounded-xl p-4 shadow-md transition-all duration-300 border-2 {{ $cardClasses }} flex flex-col items-center justify-between h-40 cursor-pointer overflow-hidden">

                            {{-- Reservation badge --}}
                            @if($pendingRes)
                                <div class="absolute top-0 left-0 bg-purple-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-br-lg z-10"
                                     title="จองพื้นที่: {{ $pendingRes->product ? $pendingRes->product->name : 'ไม่ระบุสินค้า' }}">
                                    🔖 จอง
                                </div>
                            @endif

                            {{-- Status dots --}}
                            <div class="absolute top-2 right-2 flex gap-1">
                                @if($loc->status === 'inactive')
                                    <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm" title="ปิดใช้งาน"></span>
                                @elseif($loc->status === 'full')
                                    <span class="w-3 h-3 rounded-full bg-orange-500 shadow-sm" title="เต็ม (Full)"></span>
                                @else
                                    @if($totalQty > 0)
                                        <span class="w-3 h-3 rounded-full bg-blue-600 shadow-sm" title="มีสินค้า"></span>
                                    @endif
                                    @if($totalReservedLoc > 0)
                                        <span class="w-3 h-3 rounded-full bg-yellow-500 shadow-sm" title="มีการจอง"></span>
                                    @endif
                                    @if($pendingRes)
                                        <span class="w-3 h-3 rounded-full bg-purple-500 shadow-sm" title="จองรอสินค้าเข้า"></span>
                                    @endif
                                    @if($totalQty <= 0 && $totalReservedLoc <= 0 && !$pendingRes)
                                        <span class="w-3 h-3 rounded-full bg-green-500 shadow-sm" title="ว่าง"></span>
                                    @endif
                                @endif
                            </div>

                            {{-- Location Info --}}
                            <div class="text-center">
                                <span class="text-xs font-bold uppercase tracking-wider opacity-60">{{ $loc->name }}</span>
                                <div class="text-3xl font-black mt-1">{{ $loc->shelf }}</div>
                                <span class="text-[10px] opacity-50">Bin: {{ $loc->bin }}</span>
                            </div>

                            {{-- Bottom Stats --}}
                            <div class="w-full text-center mt-1">
                                @if($loc->status === 'inactive')
                                    <span class="text-xs font-bold opacity-60">ปิดใช้งาน</span>
                                @else
                                    {{-- Capacity bar --}}
                                    <div class="w-full bg-black bg-opacity-10 rounded-full h-1.5 mb-1">
                                        <div class="{{ $capBarColor }} h-1.5 rounded-full transition-all" style="width: {{ $capacityPct }}%"></div>
                                    </div>
                                    <div class="text-[10px] font-semibold opacity-70 tabular-nums">
                                        {{ number_format($totalQty) }}<span class="opacity-60">/{{ number_format($capacity) }}</span>
                                        @if($pendingRes)
                                            <span class="ml-1 text-purple-700">(+{{ number_format($pendingRes->expected_qty) }})</span>
                                        @endif
                                    </div>
                                    @if($totalReservedLoc > 0)
                                        <div class="text-[9px] text-yellow-700 font-bold">กันไว้ {{ number_format($totalReservedLoc) }}</div>
                                    @endif
                                @endif
                            </div>


                            {{-- Hover Tooltip --}}
                            <div class="absolute z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 bottom-full mb-3 left-1/2 transform -translate-x-1/2 w-52 bg-gray-900 text-white p-3 rounded-lg shadow-2xl text-sm pointer-events-none">
                                <div class="font-bold text-base mb-2 border-b border-gray-700 pb-1">{{ $loc->name }}</div>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between"><span>📦 สินค้า:</span> <span class="font-bold text-green-400">{{ number_format($totalQty) }} ชิ้น</span></div>
                                    <div class="flex justify-between"><span>🏷️ จองแล้ว:</span> <span class="font-bold text-yellow-400">{{ number_format($totalReservedLoc) }} ชิ้น</span></div>
                                    <div class="flex justify-between"><span>✅ พร้อมเบิก:</span> <span class="font-bold text-blue-400">{{ number_format($availableLoc) }} ชิ้น</span></div>
                                    <div class="flex justify-between"><span>📋 จำนวน Lot:</span> <span class="font-bold">{{ $productCount }}</span></div>
                                    <div class="flex justify-between pt-1 border-t border-gray-700 mt-1"><span>สถานะ:</span> <span>{{ $statusLabel }}</span></div>
                                </div>
                                <svg class="absolute text-gray-900 h-3 w-full left-0 top-full" viewBox="0 0 255 255"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- Modal --}}
    <div id="locationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex justify-center items-end sm:items-center backdrop-blur-sm p-0 sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl w-full sm:w-11/12 md:w-3/4 lg:w-1/2 max-h-[90vh] sm:max-h-[85vh] flex flex-col">

            <div class="p-4 sm:p-5 border-b flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <span class="text-2xl sm:text-3xl flex-shrink-0">📍</span>
                    <h3 class="text-base sm:text-xl font-bold text-gray-800 truncate">พื้นที่: <span id="modalTitleName" class="text-blue-600">...</span></h3>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 font-bold text-3xl leading-none transition flex-shrink-0 ml-2">&times;</button>
            </div>

            <div class="p-4 sm:p-6 overflow-y-auto bg-white flex-1">
                {{-- Summary inside modal --}}
                <div id="modalSummary" class="hidden grid grid-cols-3 gap-2 sm:gap-3 mb-4">
                    <div class="bg-blue-50 rounded-lg p-2 sm:p-3 text-center">
                        <p class="text-xs text-gray-500">สินค้าทั้งหมด</p>
                        <p id="modalTotalQty" class="text-lg sm:text-xl font-black text-blue-600">0</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-2 sm:p-3 text-center">
                        <p class="text-xs text-gray-500">ถูกจอง</p>
                        <p id="modalTotalReserved" class="text-lg sm:text-xl font-black text-yellow-600">0</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-2 sm:p-3 text-center">
                        <p class="text-xs text-gray-500">พร้อมเบิก</p>
                        <p id="modalTotalAvailable" class="text-lg sm:text-xl font-black text-green-600">0</p>
                    </div>
                </div>

                <div id="loadingIndicator" class="text-center py-10 hidden">
                    <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium animate-pulse">กำลังค้นหาข้อมูลสินค้า...</p>
                </div>

                <div id="emptyMessage" class="text-center py-10 hidden">
                    <span class="text-5xl block mb-4">🪹</span>
                    <h4 class="text-lg font-bold text-gray-700">ไม่มีสินค้าในพื้นที่นี้</h4>
                    <p class="text-gray-500 text-sm mt-1">พื้นที่นี้พร้อมสำหรับจัดเก็บสินค้าใหม่</p>
                </div>

                <div id="itemsTableContainer" class="hidden rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm min-w-[480px]">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="p-2 sm:p-3">SKU</th>
                                    <th class="p-2 sm:p-3">ชื่อสินค้า</th>
                                    <th class="p-2 sm:p-3 text-center whitespace-nowrap">Lot / วันรับ</th>
                                    <th class="p-2 sm:p-3 text-center">จำนวน</th>
                                    <th class="p-2 sm:p-3 text-center">จอง</th>
                                    <th class="p-2 sm:p-3 text-center whitespace-nowrap">พร้อมเบิก</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-3 sm:p-4 border-t text-right bg-gray-50 rounded-b-2xl">
                <button onclick="closeModal()" class="bg-gray-800 hover:bg-black text-white px-6 sm:px-8 py-2.5 rounded-xl font-medium transition shadow-md w-full sm:w-auto">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>

    <script>
        function openLocationPopup(locationId, locationName) {
            document.getElementById('locationModal').classList.remove('hidden');
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
                        data.items.forEach(item => {
                            const reservedBadge = item.reserved > 0
                                ? `<span class="bg-yellow-100 text-yellow-700 font-bold px-2 py-0.5 rounded-full">${item.reserved}</span>`
                                : `<span class="text-gray-300">0</span>`;

                            html += `
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="p-3 font-mono text-xs text-gray-500">${item.sku}</td>
                                    <td class="p-3 font-medium text-gray-900">${item.product_name}</td>
                                    <td class="p-3 text-center text-xs text-gray-400">
                                        ${item.lot_number}<br>${item.received_date}
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded-full">${item.quantity}</span>
                                    </td>
                                    <td class="p-3 text-center">${reservedBadge}</td>
                                    <td class="p-3 text-center">
                                        <span class="bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">${item.available}</span>
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
            document.getElementById('locationModal').classList.add('hidden');
        }

        document.getElementById('locationModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</x-app-layout>