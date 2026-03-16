<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            Scanner
        </h2>
    </x-slot>

    {{-- Full-screen Focus Mode Layout --}}
    <div class="relative min-h-[calc(100vh-4rem)] w-full bg-slate-900 flex flex-col transition-all duration-300">
        
        {{-- Background glow --}}
        <div id="bg-glow" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[80vh] bg-blue-500/20 blur-[120px] rounded-full pointer-events-none transition-colors duration-500"></div>

        <div class="flex-1 w-full max-w-2xl mx-auto px-4 py-6 sm:py-10 flex flex-col relative z-10">

            {{-- Flash Messages --}}
            <div id="flash-messages" class="w-full mb-6">
                @if (session('success'))
                    <div class="bg-emerald-500/20 border border-emerald-500/50 backdrop-blur-md text-emerald-100 px-5 py-4 rounded-2xl shadow-[0_8px_30px_rgba(16,185,129,0.2)] flex items-start gap-3">
                        <div class="bg-emerald-500 text-white rounded-lg p-1 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                        <div>
                            <p class="font-bold text-sm text-emerald-50">สำเร็จ!</p>
                            <p class="text-xs font-medium text-emerald-200/90">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-rose-500/20 border border-rose-500/50 backdrop-blur-md text-rose-100 px-5 py-4 rounded-2xl shadow-[0_8px_30px_rgba(244,63,94,0.2)] flex items-start gap-3">
                        <div class="bg-rose-500 text-white rounded-lg p-1 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <p class="font-bold text-sm text-rose-50">พบข้อผิดพลาด</p>
                            <ul class="list-disc list-inside space-y-0.5 mt-1 font-medium text-xs text-rose-200/90">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Header / Mode Toggle --}}
            <div class="mb-6 sm:mb-10">
                <div class="bg-slate-800/80 backdrop-blur-xl p-1.5 rounded-[1.5rem] border border-slate-700/50 shadow-2xl flex">
                    <button id="tab-inbound" onclick="switchMode('inbound')"
                        class="flex-1 py-3.5 text-center font-bold text-sm sm:text-base rounded-xl transition-all duration-300 shadow-sm
                               bg-blue-600 text-white ring-1 ring-blue-500/50">
                        📥 รับเข้าคลัง (Inbound)
                    </button>
                    <button id="tab-outbound" onclick="switchMode('outbound')"
                        class="flex-1 py-3.5 text-center font-bold text-sm sm:text-base rounded-xl transition-all duration-300
                               text-slate-400 hover:text-slate-200 hover:bg-slate-700/50">
                        📤 เบิกสินค้า (Outbound)
                    </button>
                </div>
            </div>

            {{-- Scanner Viewfinder --}}
            <div class="w-full bg-slate-800/50 backdrop-blur-md rounded-[2rem] border border-slate-700/50 shadow-2xl overflow-hidden mb-6 flex flex-col">
                <div class="p-4 sm:p-5 flex items-center justify-between border-b border-slate-700/50 bg-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700 text-slate-300 flex items-center justify-center text-xl shadow-inner border border-slate-600">📷</div>
                        <div>
                            <h3 class="font-black text-slate-100 tracking-tight">กล้องสแกนบาร์โค้ด</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Camera Scanner</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button id="btn-start" onclick="startScanner()"
                            class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold py-2 px-5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> เปิดกล้อง
                        </button>
                        <button id="btn-stop" onclick="stopScanner()"
                            class="hidden bg-slate-700 hover:bg-slate-600 text-slate-300 border border-slate-600 font-bold py-2 px-5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                            ⏹ ปิดกล้อง
                        </button>
                    </div>
                </div>

                {{-- Camera Feed Area --}}
                <div class="relative w-full aspect-square sm:aspect-video bg-black max-h-[400px]">
                    <div id="reader" class="absolute inset-0 w-full h-full"></div>
                    
                    {{-- Overlay Target (CSS drawn) --}}
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="w-2/3 h-1/2 border-2 border-white/20 rounded-2xl relative">
                            <!-- Corners -->
                            <div class="absolute -top-1 -left-1 w-8 h-8 border-t-4 border-l-4 border-blue-500 rounded-tl-2xl"></div>
                            <div class="absolute -top-1 -right-1 w-8 h-8 border-t-4 border-r-4 border-blue-500 rounded-tr-2xl"></div>
                            <div class="absolute -bottom-1 -left-1 w-8 h-8 border-b-4 border-l-4 border-blue-500 rounded-bl-2xl"></div>
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 border-b-4 border-r-4 border-blue-500 rounded-br-2xl"></div>
                            <!-- Crosshair -->
                            <div id="scan-laser" class="absolute top-1/2 left-0 w-full h-[2px] bg-red-500/80 shadow-[0_0_10px_rgba(239,68,68,0.8)] -translate-y-1/2 hidden"></div>
                        </div>
                    </div>
                </div>

                {{-- Manual Input --}}
                <div class="p-4 sm:p-5 bg-slate-800/80 border-t border-slate-700/50">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">หรือระบุบาร์โค้ดด้วยตนเอง</p>
                    <div class="flex gap-2">
                        <div class="relative flex-1 group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-400">
                                <span class="text-slate-500 text-lg group-focus-within:text-blue-400">⌨️</span>
                            </div>
                            <input type="text" id="manual-barcode" placeholder="พิมพ์บาร์โค้ด..." autocomplete="off"
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-900 border border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold transition-all shadow-inner text-slate-200 placeholder-slate-500">
                        </div>
                        <button onclick="manualLookup()"
                            class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-3.5 px-6 rounded-xl shadow-md transition-all text-sm border border-slate-600 flex items-center justify-center shrink-0">
                            ค้นหา
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product Info & Action Card (Hidden initially) --}}
            <div id="product-card" class="hidden w-full bg-slate-800/90 backdrop-blur-xl rounded-[2rem] border-2 border-emerald-500/50 shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden mb-10 transform translate-y-4 opacity-0 transition-all duration-500">
                
                {{-- Success Header --}}
                <div id="result-header" class="bg-emerald-500 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white text-sm backdrop-blur-sm">✓</div>
                        <h3 class="font-black text-white tracking-tight text-lg">พบข้อมูลสินค้า</h3>
                    </div>
                    <button onclick="resetScanner()" class="w-8 h-8 bg-black/10 hover:bg-black/20 rounded-full flex items-center justify-center text-white transition-colors backdrop-blur-sm" title="ล้างข้อมูล">✕</button>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    {{-- Product Details --}}
                    <div class="bg-slate-900/50 rounded-2xl p-5 border border-slate-700/50">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">ชื่อสินค้า (Product)</p>
                                <p id="info-name" class="text-xl font-bold text-slate-100 leading-tight">-</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">รหัส SKU</p>
                                <p id="info-sku" class="text-lg font-mono font-bold text-emerald-400 bg-emerald-400/10 inline-block px-2 py-0.5 rounded border border-emerald-400/20">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-700">
                            <div class="text-center">
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">สต็อกรวม</p>
                                <p id="info-stock" class="text-2xl font-black text-blue-400">0</p>
                            </div>
                            <div class="text-center border-l border-slate-700">
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">จองแล้ว</p>
                                <p id="info-reserved" class="text-2xl font-black text-amber-400">0</p>
                            </div>
                            <div class="text-center border-l border-slate-700">
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">พร้อมเบิก</p>
                                <p id="info-available" class="text-2xl font-black text-emerald-400">0</p>
                            </div>
                        </div>
                    </div>

                    {{-- Forms --}}
                    <div id="action-form">
                        {{-- Inbound Form --}}
                        <form id="form-inbound" action="{{ route('inbound.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="barcode" id="form-barcode-in">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">จำนวนรับเข้า <span class="text-rose-500">*</span></label>
                                    <input type="number" name="quantity" min="1" required
                                        class="w-full bg-slate-900 border border-slate-700 rounded-xl text-lg font-bold text-slate-100 px-4 py-3 placeholder-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center"
                                        placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">ตำแหน่งเก็บ (Zone/Bin) <span class="text-rose-500">*</span></label>
                                    <select name="location_id" required
                                        class="w-full bg-slate-900 border border-slate-700 rounded-xl text-base font-bold text-slate-100 px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- ระบุ Location --</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }} (ว่าง: {{ ($location->capacity ?? 5000) - ($location->stocks_sum_quantity ?? 0) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">Lot Number</label>
                                <input type="text" name="lot_number"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl text-base font-mono text-slate-100 px-4 py-3 placeholder-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="ปล่อยว่างเพื่อสร้างอัตโนมัติ">
                            </div>

                            <button type="submit"
                                class="w-full mt-2 bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-6 rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all hover:-translate-y-1 text-lg flex items-center justify-center gap-2 border border-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                ยืนยันรับเข้าคลัง
                            </button>
                        </form>

                        {{-- Outbound Form --}}
                        <form id="form-outbound" class="hidden space-y-5" action="{{ route('outbound.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="barcode" id="form-barcode-out">
                            
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 mb-1.5 uppercase tracking-widest">จำนวนต้องการเบิก <span class="text-rose-500">*</span></label>
                                <input type="number" name="quantity" id="out-quantity" min="1" required
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl text-[2rem] h-16 font-black text-slate-100 px-4 py-2 placeholder-slate-600 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-center"
                                    placeholder="0">
                                
                                <div id="out-stock-warning" class="hidden mt-3 bg-rose-500/20 border border-rose-500/50 rounded-lg p-3 flex items-start gap-2">
                                    <span class="text-rose-400 text-sm">⚠️</span>
                                    <p class="text-rose-200 text-xs font-bold leading-tight pt-0.5">ระบุเยอะกว่าที่มีในคลัง <br><span class="font-medium opacity-80">(สูงสุด: <span id="max-allowed-text">0</span>)</span></p>
                                </div>
                            </div>

                            <button type="submit" id="out-submit-btn"
                                class="w-full mt-2 bg-rose-600 hover:bg-rose-500 text-white font-bold py-4 px-6 rounded-xl shadow-[0_0_20px_rgba(225,29,72,0.4)] transition-all hover:-translate-y-1 text-lg flex items-center justify-center gap-2 border border-rose-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                ยืนยันเบิกสินค้า
                            </button>
                        </form>
                    </div>

                </div>
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
            const productCard = document.getElementById('product-card');
            const resultHeader = document.getElementById('result-header');
            const bgGlow = document.getElementById('bg-glow');

            if (mode === 'inbound') {
                tabIn.className = 'flex-1 py-3.5 text-center font-bold text-sm sm:text-base rounded-xl transition-all duration-300 shadow-sm bg-blue-600 text-white ring-1 ring-blue-500/50';
                tabOut.className = 'flex-1 py-3.5 text-center font-bold text-sm sm:text-base rounded-xl transition-all duration-300 text-slate-400 hover:text-slate-200 hover:bg-slate-700/50';
                formIn.classList.remove('hidden');
                formOut.classList.add('hidden');
                
                productCard.style.borderColor = 'rgba(16, 185, 129, 0.5)'; // Emerald
                resultHeader.className = 'bg-emerald-500 px-6 py-4 flex items-center justify-between';
                bgGlow.className = 'fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[80vh] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none transition-colors duration-500';

            } else {
                tabIn.className = 'flex-1 py-3.5 text-center font-bold text-sm sm:text-base rounded-xl transition-all duration-300 text-slate-400 hover:text-slate-200 hover:bg-slate-700/50';
                tabOut.className = 'flex-1 py-3.5 text-center font-bold text-sm sm:text-base rounded-xl transition-all duration-300 shadow-sm bg-rose-600 text-white ring-1 ring-rose-500/50';
                formIn.classList.add('hidden');
                formOut.classList.remove('hidden');
                
                productCard.style.borderColor = 'rgba(225, 29, 72, 0.5)'; // Rose
                resultHeader.className = 'bg-rose-500 px-6 py-4 flex items-center justify-between';
                bgGlow.className = 'fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[80vh] bg-rose-500/10 blur-[120px] rounded-full pointer-events-none transition-colors duration-500';
            }
        }

        // ======== Scanner ========
        function startScanner() {
            if (isScanning) return;

            html5QrCode = new Html5Qrcode("reader");
            const laser = document.getElementById('scan-laser');

            html5QrCode.start(
                { facingMode: "environment" },
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
                laser.classList.remove('hidden');
            }).catch(err => {
                alert('❌ ไม่สามารถเปิดกล้องได้: ' + err);
            });
        }

        function stopScanner() {
            const laser = document.getElementById('scan-laser');
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    isScanning = false;
                    document.getElementById('btn-start').classList.remove('hidden');
                    document.getElementById('btn-stop').classList.add('hidden');
                    laser.classList.add('hidden');
                    document.getElementById('reader').innerHTML = ''; // clear completely
                });
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            stopScanner();
            playBeep();
            document.getElementById('manual-barcode').value = decodedText;
            fetchProduct(decodedText);
        }

        function onScanFailure(error) {}

        function playBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                osc.type = 'square';
                osc.frequency.value = 800;
                osc.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.1);
            } catch(e) {}
        }

        // ======== Manual Input ========
        function manualLookup() {
            const val = document.getElementById('manual-barcode').value.trim();
            if (val) fetchProduct(val);
        }

        document.getElementById('manual-barcode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                manualLookup();
            }
        });

        // ======== Fetch Product Data ========
        function fetchProduct(barcode) {
            
            // Show loading state roughly
            document.getElementById('manual-barcode').disabled = true;
            
            fetch(`/api/products/${encodeURIComponent(barcode)}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('manual-barcode').disabled = false;
                    if (data.success) {
                        showProductInfo(data.product, barcode);
                    } else {
                        showError('ไม่พบสินค้าสำหรับบาร์โค้ด: ' + barcode);
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('manual-barcode').disabled = false;
                    showError('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                });
        }

        function showProductInfo(product, barcode) {
            // Populate
            document.getElementById('info-name').innerText = product.name;
            document.getElementById('info-sku').innerText = product.sku;

            const totalStock = parseInt(product.total_stock) || 0;
            const reserved = parseInt(product.reserved || 0);
            const available = totalStock - reserved;

            document.getElementById('info-stock').innerText = totalStock.toLocaleString();
            document.getElementById('info-reserved').innerText = reserved.toLocaleString();
            document.getElementById('info-available').innerText = available.toLocaleString();

            currentMaxStock = available;
            document.getElementById('max-allowed-text').innerText = available.toLocaleString();

            // Set forms
            document.getElementById('form-barcode-in').value = barcode;
            document.getElementById('form-barcode-out').value = barcode;
            
            // Output validation reset
            document.getElementById('out-quantity').value = '';
            document.getElementById('out-stock-warning').classList.add('hidden');
            const outBtn = document.getElementById('out-submit-btn');
            outBtn.disabled = false;
            outBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'saturate-0');

            // Show card with animation
            const card = document.getElementById('product-card');
            card.classList.remove('hidden');
            
            // trigger reflow
            void card.offsetWidth;
            
            card.classList.remove('translate-y-4', 'opacity-0');
            card.classList.add('translate-y-0', 'opacity-100');
            
            // Scroll to it
            setTimeout(() => {
                card.scrollIntoView({ behavior: 'smooth', block: 'end' });
                // Auto focus qty input based on mode
                if (currentMode === 'inbound') {
                    document.querySelector('#form-inbound input[name="quantity"]').focus();
                } else {
                    document.getElementById('out-quantity').focus();
                }
            }, 300);
        }

        function resetScanner() {
            const card = document.getElementById('product-card');
            card.classList.remove('translate-y-0', 'opacity-100');
            card.classList.add('translate-y-4', 'opacity-0');
            
            setTimeout(() => {
                card.classList.add('hidden');
                document.getElementById('manual-barcode').value = '';
                document.getElementById('manual-barcode').focus();
                currentMaxStock = 0;
            }, 300);
        }

        function showError(msg) {
            alert('⚠️ ' + msg);
            document.getElementById('manual-barcode').value = '';
            document.getElementById('manual-barcode').focus();
        }

        // ======== Outbound stock validation ========
        document.getElementById('out-quantity').addEventListener('input', function() {
            const val = parseInt(this.value) || 0;
            const warning = document.getElementById('out-stock-warning');
            const btn = document.getElementById('out-submit-btn');

            if (val > currentMaxStock) {
                warning.classList.remove('hidden');
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'saturate-0');
            } else {
                warning.classList.add('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed', 'saturate-0');
            }
        });
        
        // Style html5-qrcode UI
        const styleHtml5QrCode = () => {
             const reader = document.getElementById('reader');
             if(reader) {
                 const btn = reader.querySelector('button');
                 if(btn) btn.className = 'bg-slate-700 text-white px-4 py-2 rounded-lg text-sm';
             }
        }
        setInterval(styleHtml5QrCode, 1000);
    </script>
</x-app-layout>
