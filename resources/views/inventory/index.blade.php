<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📊 รายการสินค้าคงคลัง (Inventory List)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap min-w-[900px]">
                            <thead>
                                <tr class="bg-gray-200" align="center">
                                    <th class="p-3 border">Barcode</th>
                                    <th class="p-3 border">ชื่อสินค้า</th>
                                    <th class="p-3 border">สต็อกทั้งหมด</th>
                                    <th class="p-3 border">ถูกจอง (Reserve)</th>
                                    <th class="p-3 border text-green-600">คงเหลือพร้อมจ่าย</th>
                                    <th class="p-3 border">พิมพ์สติกเกอร์</th>
                                    <th class="p-3 border">QR Code</th>
                                    <th class="p-3 border">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-50" align="center">
                                        <td class="p-3 border font-mono text-sm">{{ $product->barcode }}</td>
                                        <td class="p-3 border">{{ $product->name }}</td>
                                        <td class="p-3 border text-blue-600 font-bold">
                                            {{ number_format($product->stocks_sum_quantity ?? 0) }}
                                        </td>
                                        <td class="p-3 border text-red-500">
                                            {{ number_format($product->stocks_sum_reserved_qty ?? 0) }}
                                        </td>
                                        <td class="p-3 border text-green-600 font-bold">
                                            {{ number_format(($product->stocks_sum_quantity ?? 0) - ($product->stocks_sum_reserved_qty ?? 0)) }}
                                        </td>
                                        <td class="p-3 border">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                @if($product->barcode_image)
                                                    <img id="barcode-img-{{ $product->id }}" 
                                                         src="{{ asset('storage/barcodes/' . $product->barcode_image) }}" 
                                                         style="height: 33px; width: auto;" 
                                                         alt="barcode">
                                                @else
                                                    <span class="text-xs text-red-500 font-bold bg-red-100 px-2 py-1 rounded">ไม่มีรูปภาพ</span>
                                                @endif

                                                <button type="button" 
                                                    onclick="printStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')"
                                                    class="bg-gray-800 hover:bg-black text-white py-1 px-4 rounded-full text-xs shadow-sm transition">
                                                    🖨️ พิมพ์
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-3 border">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                @if($product->qr_code_image)
                                                    <img id="qrcode-img-{{ $product->id }}" 
                                                         src="{{ asset('storage/qrcodes/' . $product->qr_code_image) }}" 
                                                         style="height: 50px; width: 50px;" 
                                                         alt="QR Code"
                                                         class="border rounded">
                                                @else
                                                    <span class="text-xs text-red-500 font-bold bg-red-100 px-2 py-1 rounded">ไม่มี QR</span>
                                                @endif

                                                <button type="button" 
                                                    onclick="printQrStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')"
                                                    class="bg-indigo-600 hover:bg-indigo-800 text-white py-1 px-4 rounded-full text-xs shadow-sm transition">
                                                    📱 พิมพ์ QR
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-3 border text-center">
                                            <div class="flex flex-col gap-2">
                                                <button onclick="openReservationModal('{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $product->stocks_sum_quantity ?? 0 }}, {{ $product->stocks_sum_reserved_qty ?? 0 }})"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-xs font-bold shadow-sm transition">
                                                    🔒 จอง/ปลด
                                                </button>

                                                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                                                    onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสินค้านี้?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition text-xs font-bold">
                                                        ลบรายการ
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> </div>
            </div>
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

                <!-- Tabs -->
                <div class="flex border-b">
                    <button id="tabReserve" onclick="switchTab('reserve')" class="flex-1 py-2 font-bold text-yellow-600 border-b-2 border-yellow-500 bg-yellow-50">🔒 จองสินค้า (Reserve)</button>
                    <button id="tabRelease" onclick="switchTab('release')" class="flex-1 py-2 font-bold text-gray-500 hover:text-green-600">🔓 ปลดจอง (Release)</button>
                </div>

                <!-- Form -->
                <form id="reservationForm" method="POST" action="{{ route('reservation.reserve') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="resProductId">
                    
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวน (Qty):</label>
                        <input type="number" name="quantity" min="1" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" onclick="closeReservationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            ยกเลิก
                        </button>
                        <button type="submit" id="resSubmitBtn" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            ยืนยันการจอง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReservationModal(id, name, total, reserved) {
            document.getElementById('reservationModal').classList.remove('hidden');
            
            document.getElementById('resProductId').value = id;
            document.getElementById('resModalName').innerText = name;
            
            document.getElementById('resModalTotal').innerText = total;
            document.getElementById('resModalReserved').innerText = reserved;
            document.getElementById('resModalAvailable').innerText = total - reserved;

            // default tab
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
            // 1. ดึง Source (URL) ของรูปภาพจากหน้าเว็บ
            const imgElement = document.getElementById(`barcode-img-${productId}`);
            
            if (!imgElement) {
                alert("❌ ไม่พบรูปภาพบาร์โค้ดสำหรับสินค้านี้ (อาจต้องบันทึกสินค้าใหม่)");
                return;
            }

            const imgSrc = imgElement.src;

            // 2. สร้างโครงสร้างสติกเกอร์ (ใช้แท็ก <img> เรียกรูปมาแสดง)
            const printContent = `
                <div id="printableSticker" style="width: 50mm; height: 30mm; background: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-sizing: border-box; padding: 5px;">
                    <div style="font-size: 14px; font-weight: 600; font-family: sans-serif; margin-bottom: 2px; white-space: nowrap; overflow: hidden; width: 100%; color: black;">${name}</div>
                    
                    <img src="${imgSrc}" style="height: 33px; width: auto; margin-bottom: 2px;">
                    
                    <div style="font-size: 12px; font-family: monospace; font-weight: 500; color: black;">${sku}</div>
                </div>
            `;

            // 3. แทนที่หน้าเว็บเพื่อเตรียมพิมพ์ + เคลียร์ CSS ขยะ
            document.body.innerHTML = `
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-print-color-adjust: exact; }
                    @page { size: 50mm 30mm; margin: 0; }
                    body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: white; }
                </style>
                ${printContent}
            `;

            // 4. สั่งพิมพ์แล้วรีโหลด (ใส่ setTimeout กันรูปบาร์โค้ดโหลดไม่ทันตอนปริ้น)
            setTimeout(() => {
                window.print();
                window.location.reload();
            }, 200); 
        }

        function printQrStickerDirect(name, sku, productId) {
            const imgElement = document.getElementById(`qrcode-img-${productId}`);
            
            if (!imgElement) {
                alert("❌ ไม่พบรูปภาพ QR Code สำหรับสินค้านี้ (อาจต้องบันทึกสินค้าใหม่)");
                return;
            }

            const imgSrc = imgElement.src;

            const printContent = `
                <div style="width: 50mm; height: 50mm; background: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-sizing: border-box; padding: 5px;">
                    <div style="font-size: 14px; font-weight: 600; font-family: sans-serif; margin-bottom: 4px; white-space: nowrap; overflow: hidden; width: 100%; color: black;">${name}</div>
                    <img src="${imgSrc}" style="width: 30mm; height: 30mm; margin-bottom: 4px; image-rendering: pixelated;">
                    <div style="font-size: 12px; font-family: monospace; font-weight: 500; color: black;">${sku}</div>
                </div>
            `;

            document.body.innerHTML = `
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-print-color-adjust: exact; }
                    @page { size: 50mm 50mm; margin: 0; }
                    body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: white; }
                </style>
                ${printContent}
            `;

            setTimeout(() => {
                window.print();
                window.location.reload();
            }, 200);
        }
    </script>
</x-app-layout>