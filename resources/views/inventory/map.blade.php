<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7m0 0L9 4" />
            </svg>
            Warehouse Visual Map
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-12 relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-lg mb-8 flex flex-wrap justify-center gap-6 border border-gray-200">
                <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-200">
                    <span class="w-4 h-4 rounded-full bg-green-500 shadow-sm"></span>
                    <span class="font-medium text-green-800">ว่าง (Empty)</span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full border border-blue-200">
                    <span class="w-4 h-4 rounded-full bg-blue-600 shadow-sm"></span>
                    <span class="font-medium text-blue-800">มีสินค้า (Occupied)</span>
                </div>
                <div class="flex items-center gap-2 bg-yellow-50 px-4 py-2 rounded-full border border-yellow-200">
                    <span class="w-4 h-4 rounded-full bg-yellow-500 shadow-sm"></span>
                    <span class="font-medium text-yellow-800">จองพื้นที่ (Reserved)</span>
                </div>
            </div>

            @foreach($zones as $zoneName => $locations)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-indigo-600 w-2 h-10 rounded-full"></div>
                    <h3 class="text-3xl font-bold text-gray-800">Zone: {{ $zoneName }}</h3>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-6">
                    @foreach($locations as $loc)
                        @php
                            $totalQty = $loc->stocks->sum('quantity');
                            $totalReserved = $loc->stocks->sum('reserved_qty');
                            
                            $cardClasses = 'bg-green-50 border-green-300 text-green-900 hover:bg-green-100 hover:border-green-400';
                            $dotColor = 'bg-green-500';

                            if ($totalQty > 0) {
                                $cardClasses = 'bg-blue-100 border-blue-300 text-blue-900 hover:bg-blue-200 hover:border-blue-400';
                                $dotColor = 'bg-blue-600';
                            } elseif ($totalReserved > 0) {
                                $cardClasses = 'bg-yellow-50 border-yellow-300 text-yellow-900 hover:bg-yellow-100 hover:border-yellow-400';
                                $dotColor = 'bg-yellow-500';
                            }
                        @endphp

                        <div onclick="openLocationPopup({{ $loc->id }}, '{{ addslashes($loc->name) }}')" class="relative group rounded-2xl p-5 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-2 {{ $cardClasses }} flex flex-col items-center justify-center h-36 cursor-pointer overflow-hidden">
                            
                            <span class="absolute top-3 right-3 w-3 h-3 rounded-full {{ $dotColor }} shadow-sm"></span>

                            <span class="text-xs font-bold uppercase tracking-wider opacity-70 mb-1">Bin: {{ $loc->bin }}</span>
                            <span class="text-4xl font-extrabold">{{ $loc->shelf }}</span>
                            
                            <div class="absolute z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 bottom-full mb-3 left-1/2 transform -translate-x-1/2 w-48 bg-gray-900 text-white p-3 rounded-lg shadow-2xl text-sm pointer-events-none">
                                <div class="font-bold text-base mb-2 border-b border-gray-700 pb-1">{{ $loc->name }}</div>
                                <div class="space-y-1">
                                    <div class="flex justify-between"><span>📦 สินค้า:</span> <span class="font-bold text-green-400">{{ number_format($totalQty) }}</span></div>
                                    <div class="flex justify-between"><span>🏷️ จองแล้ว:</span> <span class="font-bold text-yellow-400">{{ number_format($totalReserved) }}</span></div>
                                    <div class="flex justify-between pt-1 border-t border-gray-700 mt-1"><span>สถานะ:</span> <span class="capitalize">{{ $loc->status ?? 'Active' }}</span></div>
                                </div>
                                <svg class="absolute text-gray-900 h-3 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <div id="locationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex justify-center items-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[85vh] flex flex-col transform transition-all">
            
            <div class="p-5 border-b flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">📍</span>
                    <h3 class="text-xl font-bold text-gray-800">รายการสินค้าในพื้นที่: <span id="modalTitleName" class="text-blue-600">...</span></h3>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 font-bold text-3xl leading-none transition">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto bg-white flex-1">
                
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
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="p-4 font-semibold">SKU</th>
                                <th class="p-4 font-semibold">ชื่อสินค้า</th>
                                <th class="p-4 font-semibold text-center">คงเหลือ (Qty)</th>
                                <th class="p-4 font-semibold text-center">ถูกจอง (Reserved)</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody" class="divide-y divide-gray-100">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="p-4 border-t text-right bg-gray-50 rounded-b-2xl">
                <button onclick="closeModal()" class="bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl font-medium transition shadow-md">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>

    <script>
        function openLocationPopup(locationId, locationName) {
            // เปิดหน้าต่างและตั้งชื่อ
            document.getElementById('locationModal').classList.remove('hidden');
            document.getElementById('modalTitleName').innerText = locationName;
            
            // สลับสถานะ UI
            document.getElementById('itemsTableContainer').classList.add('hidden');
            document.getElementById('emptyMessage').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('itemsTableBody').innerHTML = '';

            // ⚠️ ต้องมี API Route เพื่อดึงข้อมูลนี้นะครับ
            fetch(`/api/locations/${locationId}/items`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    if (data.items.length === 0) {
                        document.getElementById('emptyMessage').classList.remove('hidden');
                    } else {
                        document.getElementById('itemsTableContainer').classList.remove('hidden');
                        
                        let html = '';
                        data.items.forEach(item => {
                            html += `
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="p-4 font-mono text-xs text-gray-600">${item.sku}</td>
                                    <td class="p-4 font-medium text-gray-900">${item.product_name}</td>
                                    <td class="p-4 text-center">
                                        <span class="bg-green-100 text-green-800 font-bold px-3 py-1 rounded-full">${item.quantity}</span>
                                    </td>
                                    <td class="p-4 text-center text-yellow-600 font-semibold">${item.reserved}</td>
                                </tr>
                            `;
                        });
                        document.getElementById('itemsTableBody').innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    alert('ไม่สามารถดึงข้อมูลได้ โปรดลองอีกครั้ง');
                });
        }

        function closeModal() {
            document.getElementById('locationModal').classList.add('hidden');
        }

        // ปิด Modal เมื่อคลิกพื้นที่สีดำรอบนอก
        document.getElementById('locationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-app-layout>