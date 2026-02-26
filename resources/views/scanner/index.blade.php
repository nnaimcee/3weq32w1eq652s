<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📷 สแกน QR Code / Barcode ด้วยกล้อง
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Mode Tabs --}}
            <div class="flex mb-4 bg-white rounded-xl shadow overflow-hidden">
                <button id="tab-inbound" onclick="switchMode('inbound')"
                    class="flex-1 py-3 text-center font-bold text-blue-600 bg-blue-50 border-b-4 border-blue-500 transition">
                    📥 รับเข้า (Inbound)
                </button>
                <button id="tab-outbound" onclick="switchMode('outbound')"
                    class="flex-1 py-3 text-center font-bold text-gray-500 hover:text-red-600 transition">
                    📤 เบิกออก (Outbound)
                </button>
            </div>

            {{-- Camera Scanner --}}
            <div class="bg-white shadow-sm sm:rounded-xl p-4 mb-4 border-2" id="scanner-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-gray-700">📷 กล้องสแกน</h3>
                    <div class="flex gap-2">
                        <button id="btn-start" onclick="startScanner()"
                            class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1.5 px-4 rounded-full transition">
                            ▶ เปิดกล้อง
                        </button>
                        <button id="btn-stop" onclick="stopScanner()"
                            class="hidden bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1.5 px-4 rounded-full transition">
                            ⏹ ปิดกล้อง
                        </button>
                    </div>
                </div>
                <div id="reader" class="w-full rounded-lg overflow-hidden bg-gray-900" style="min-height: 280px;"></div>

                {{-- Manual Input --}}
                <div class="mt-3 flex gap-2">
                    <input type="text" id="manual-barcode" placeholder="หรือพิมพ์บาร์โค้ด/สแกนเนอร์..."
                        class="flex-1 border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button onclick="manualLookup()"
                        class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                        🔍 ค้นหา
                    </button>
                </div>
            </div>

            {{-- Product Info Card (hidden until scan) --}}
            <div id="product-card" class="hidden bg-white shadow-lg sm:rounded-xl overflow-hidden mb-4 border-2 border-green-300">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-lg">✅ พบสินค้า</h3>
                        <button onclick="resetScanner()" class="text-white/80 hover:text-white text-sm">✕ ล้างข้อมูล</button>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">ชื่อสินค้า</p>
                            <p id="info-name" class="text-lg font-bold text-gray-800">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">รหัส SKU</p>
                            <p id="info-sku" class="text-lg font-mono font-bold text-gray-800">-</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold">สต็อกรวม</p>
                                <p id="info-stock" class="text-2xl font-black text-blue-600">0</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold">จองแล้ว</p>
                                <p id="info-reserved" class="text-2xl font-black text-yellow-600">0</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold">พร้อมเบิก</p>
                                <p id="info-available" class="text-2xl font-black text-green-600">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Form --}}
            <div id="action-form" class="hidden bg-white shadow-lg sm:rounded-xl p-5 border-2" style="border-color: var(--form-border-color, #3b82f6);">

                {{-- Inbound Form --}}
                <form id="form-inbound" action="{{ route('inbound.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barcode" id="form-barcode-in">
                    <h3 class="font-bold text-blue-700 mb-4 text-lg">📥 รับสินค้าเข้าคลัง</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวน (Quantity):</label>
                        <input type="number" name="quantity" min="1" required
                            class="shadow border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:border-blue-500"
                            placeholder="ระบุจำนวน">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lot Number (ถ้ามี):</label>
                        <input type="text" name="lot_number"
                            class="shadow border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:border-blue-500"
                            placeholder="ระบุ Lot ID (ปล่อยว่างระบบจะสร้างให้)">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">ตำแหน่งจัดเก็บ (Location):</label>
                        <select name="location_id" required
                            class="shadow border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:border-blue-500">
                            <option value="">-- กรุณาเลือก --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 rounded-xl text-lg shadow-lg transition">
                        💾 ยืนยันรับเข้าคลัง
                    </button>
                </form>

                {{-- Outbound Form --}}
                <form id="form-outbound" class="hidden" action="{{ route('outbound.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barcode" id="form-barcode-out">
                    <h3 class="font-bold text-red-700 mb-4 text-lg">📤 เบิกสินค้าออก</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวนที่ต้องการเบิก:</label>
                        <input type="number" name="quantity" id="out-quantity" min="1" required
                            class="shadow border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:border-red-500"
                            placeholder="ระบุจำนวน">
                        <p id="out-stock-warning" class="text-red-500 text-xs mt-1 hidden font-bold">⚠️ จำนวนที่ระบุมากกว่าสินค้าที่มีในคลัง!</p>
                    </div>

                    <button type="submit" id="out-submit-btn"
                        class="w-full bg-red-600 hover:bg-red-800 text-white font-bold py-3 rounded-xl text-lg shadow-lg transition">
                        📤 ยืนยันเบิกสินค้า
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- html5-qrcode CDN --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;
        let currentMode = 'inbound'; // 'inbound' or 'outbound'
        let currentMaxStock = 0;
        let isScanning = false;

        // ======== Mode Switching ========
        function switchMode(mode) {
            currentMode = mode;
            const tabIn = document.getElementById('tab-inbound');
            const tabOut = document.getElementById('tab-outbound');
            const formIn = document.getElementById('form-inbound');
            const formOut = document.getElementById('form-outbound');
            const actionForm = document.getElementById('action-form');

            if (mode === 'inbound') {
                tabIn.className = 'flex-1 py-3 text-center font-bold text-blue-600 bg-blue-50 border-b-4 border-blue-500 transition';
                tabOut.className = 'flex-1 py-3 text-center font-bold text-gray-500 hover:text-red-600 transition';
                formIn.classList.remove('hidden');
                formOut.classList.add('hidden');
                actionForm.style.setProperty('--form-border-color', '#3b82f6');
                actionForm.style.borderColor = '#3b82f6';
            } else {
                tabIn.className = 'flex-1 py-3 text-center font-bold text-gray-500 hover:text-blue-600 transition';
                tabOut.className = 'flex-1 py-3 text-center font-bold text-red-600 bg-red-50 border-b-4 border-red-500 transition';
                formIn.classList.add('hidden');
                formOut.classList.remove('hidden');
                actionForm.style.setProperty('--form-border-color', '#ef4444');
                actionForm.style.borderColor = '#ef4444';
            }
        }

        // ======== Scanner ========
        function startScanner() {
            if (isScanning) return;

            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" }, // ใช้กล้องหลัง
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                document.getElementById('btn-start').classList.add('hidden');
                document.getElementById('btn-stop').classList.remove('hidden');
            }).catch(err => {
                alert('❌ ไม่สามารถเปิดกล้องได้: ' + err);
            });
        }

        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    isScanning = false;
                    document.getElementById('btn-start').classList.remove('hidden');
                    document.getElementById('btn-stop').classList.add('hidden');
                });
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // หยุดสแกนชั่วคราวเพื่อไม่ให้สแกนซ้ำ
            stopScanner();

            // เสียงแจ้งเตือน (beep)
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                osc.type = 'sine';
                osc.frequency.value = 1000;
                osc.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            } catch(e) {}

            // ใส่ค่าที่สแกนได้ลงช่อง manual
            document.getElementById('manual-barcode').value = decodedText;

            // ดึงข้อมูลสินค้า
            fetchProduct(decodedText);
        }

        function onScanFailure(error) {
            // ไม่ต้องทำอะไร — สแกนไม่เจอก็วนต่อ
        }

        // ======== Manual Input ========
        function manualLookup() {
            const val = document.getElementById('manual-barcode').value.trim();
            if (val) fetchProduct(val);
        }

        // กด Enter ในช่อง manual
        document.getElementById('manual-barcode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                manualLookup();
            }
        });

        // ======== Fetch Product Data ========
        function fetchProduct(barcode) {
            fetch(`/api/products/${encodeURIComponent(barcode)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showProductInfo(data.product, barcode);
                    } else {
                        alert('❌ ไม่พบสินค้าสำหรับรหัส: ' + barcode);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('❌ เกิดข้อผิดพลาดในการค้นหาสินค้า');
                });
        }

        function showProductInfo(product, barcode) {
            // แสดงข้อมูลสินค้า
            document.getElementById('info-name').innerText = product.name;
            document.getElementById('info-sku').innerText = product.sku;

            const totalStock = parseInt(product.total_stock) || 0;
            const reserved = parseInt(product.reserved || 0);
            const available = totalStock - reserved;

            document.getElementById('info-stock').innerText = totalStock.toLocaleString();
            document.getElementById('info-reserved').innerText = reserved.toLocaleString();
            document.getElementById('info-available').innerText = available.toLocaleString();

            currentMaxStock = available;

            // ใส่ barcode ลง form
            document.getElementById('form-barcode-in').value = barcode;
            document.getElementById('form-barcode-out').value = barcode;

            // แสดง cards
            document.getElementById('product-card').classList.remove('hidden');
            document.getElementById('action-form').classList.remove('hidden');

            // scroll ลงไปที่ product card
            document.getElementById('product-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function resetScanner() {
            document.getElementById('product-card').classList.add('hidden');
            document.getElementById('action-form').classList.add('hidden');
            document.getElementById('manual-barcode').value = '';
            currentMaxStock = 0;
        }

        // ======== Outbound stock validation ========
        document.getElementById('out-quantity').addEventListener('input', function() {
            const val = parseInt(this.value);
            const warning = document.getElementById('out-stock-warning');
            const btn = document.getElementById('out-submit-btn');

            if (val > currentMaxStock) {
                warning.classList.remove('hidden');
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                warning.classList.add('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
</x-app-layout>
