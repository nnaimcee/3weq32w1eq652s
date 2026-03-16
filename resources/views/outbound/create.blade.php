<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-rose-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                เบิกสินค้าออก (Outbound)
            </h2>
        </div>
    </x-slot>

    <div class="py-6 w-full relative z-10">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                    <div class="bg-emerald-100 rounded-lg p-1 text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div>
                        <p class="font-bold">สำเร็จ!</p>
                        <p class="text-sm font-medium opacity-90">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                    <div class="bg-rose-100 rounded-lg p-1 text-rose-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div>
                        <p class="font-bold">ข้อผิดพลาด!</p>
                        <p class="text-sm font-medium opacity-90">{{ session('error') }}</p>
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

            {{-- Split Pane Form Layout --}}
            <div class="bg-white rounded-[2rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden flex flex-col lg:flex-row">
                
                {{-- Left Pane: Instructions & Summary --}}
                <div class="lg:w-1/3 bg-slate-50/50 border-b lg:border-b-0 lg:border-r border-slate-100 p-8 flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-rose-100/50 rounded-bl-full -z-10 pointer-events-none"></div>

                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-rose-200 mb-6">📤</div>
                    <h3 class="font-black text-xl text-slate-800 mb-3 tracking-tight">การเบิกสินค้าออกจากคลัง</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8">
                        กระบวนการนี้จะทำการตัดยอดสต็อกสินค้าออกจากระบบทันที <span class="bg-rose-50 text-rose-600 font-bold px-1.5 py-0.5 rounded border border-rose-100">(สินค้าจะหายไปจากคลัง)</span> นิยมใช้สำหรับการส่งมอบให้ลูกค้า หรือเบิกไปใช้งานต่างๆ
                    </p>

                    <div class="space-y-4 mt-auto">
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
                             <div class="text-blue-500 text-xl font-bold mt-0.5">1</div>
                             <div>
                                 <p class="font-bold text-slate-700 text-sm">สแกนบาร์โค้ด</p>
                                 <p class="text-xs text-slate-500 font-medium mt-0.5">ใช้เครื่องสแกนบาร์โค้ด หรือพิมพ์รหัสสินค้า</p>
                             </div>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
                             <div class="text-emerald-500 text-xl font-bold mt-0.5">2</div>
                             <div>
                                 <p class="font-bold text-slate-700 text-sm">ตรวจสอบสต็อกอัพเดท</p>
                                 <p class="text-xs text-slate-500 font-medium mt-0.5">ระบบจะดึงยอดสต็อกคงเหลือมาแสดงทันที</p>
                             </div>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
                             <div class="text-amber-500 text-xl font-bold mt-0.5">3</div>
                             <div>
                                 <p class="font-bold text-slate-700 text-sm">กดยืนยัน (ตัดสต็อก)</p>
                                 <p class="text-xs text-slate-500 font-medium mt-0.5">ระบบจะหักยอดสินค้าออกจากคลังแบบ FIFO</p>
                             </div>
                        </div>
                    </div>
                </div>

                {{-- Right Pane: Form Inputs --}}
                <div class="lg:w-2/3 p-8 relative">
                    <form action="{{ route('outbound.store') }}" method="POST" id="outboundForm" class="space-y-8 max-w-lg mx-auto py-4 relative z-10">
                        @csrf
                        
                        {{-- Step 1: Barcode Scan --}}
                        <div class="relative pl-8">
                            <div class="absolute top-0 left-0 bottom-0 w-px bg-slate-100 flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shadow-sm ring-4 ring-white absolute -top-1 -left-[11px] border border-blue-200">1</div>
                            </div>
                            
                            <label class="block text-sm font-bold text-slate-800 mb-2">สแกนบาร์โค้ดสินค้าที่ต้องการเบิก</label>
                            
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                <input type="text" name="barcode" id="barcode" required autofocus
                                    class="pl-12 w-full border border-slate-200 rounded-xl text-base font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-400 focus:bg-white transition-all shadow-inner" 
                                    placeholder="สแกน หรือพิมพ์รหัสบาร์โค้ด แล้วกด Enter...">
                            </div>
                        </div>

                        {{-- Step 2: Info Box --}}
                        <div class="relative pl-8">
                            <div class="absolute top-0 left-0 bottom-0 w-px bg-slate-100 flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs shadow-sm ring-4 ring-white absolute -top-1 -left-[11px] border border-emerald-200">2</div>
                            </div>
                            
                            <div id="product_info_box" class="hidden p-5 bg-white border border-rose-100 rounded-2xl shadow-sm relative overflow-hidden transition-all duration-300">
                                <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-rose-50 to-transparent -z-10"></div>
                                <h3 class="font-bold text-rose-800 flex items-center gap-2 mb-4">
                                    <span id="loading_spinner" class="hidden animate-spin">
                                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </span>
                                    <span>ข้อมูลสินค้าที่พบ</span>
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ชื่อสินค้า</p>
                                        <p id="display_name" class="font-bold text-base text-slate-800 leading-tight">-</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">สต็อกพร้อมจ่าย</p>
                                        <p id="display_stock" class="font-black text-2xl text-rose-600">0</p>
                                    </div>
                                </div>
                                <input type="hidden" name="product_id" id="product_id">
                            </div>
                            
                            {{-- Placeholder Box when hidden --}}
                            <div id="product_placeholder" class="p-6 bg-slate-50 border border-slate-200 border-dashed rounded-2xl flex flex-col items-center justify-center text-center">
                                <span class="text-3xl mb-2 opacity-50 block">📦</span>
                                <p class="text-sm font-bold text-slate-500">รอข้อมูลสินค้า</p>
                                <p class="text-xs text-slate-400 mt-1">สแกนบาร์โค้ดในขั้นตอนที่ 1 เพื่อดูข้อมูล</p>
                            </div>
                        </div>

                        {{-- Step 3: Quantity --}}
                         <div class="relative pl-8">
                            <div class="absolute top-0 left-0 w-px bg-transparent flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xs shadow-sm ring-4 ring-white absolute -top-1 -left-[11px] border border-amber-200">3</div>
                            </div>
                            
                            <label class="block text-sm font-bold text-slate-800 mb-2">ระบุจำนวนที่เบิก</label>
                            <div class="relative mt-2 rounded-xl shadow-sm">
                                <input type="number" name="quantity" id="quantity"
                                    class="block w-full border border-slate-200 rounded-xl text-lg font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all shadow-inner disabled:opacity-50 disabled:cursor-not-allowed"
                                    required min="1" placeholder="ระบุจำนวน..." disabled>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold">ชิ้น</span>
                                </div>
                            </div>

                            <p id="stock_warning" class="text-rose-500 text-xs mt-3 hidden font-bold flex items-center gap-1.5 bg-rose-50 p-2.5 rounded-lg border border-rose-100">
                                ⚠️ จำนวนที่ระบุมากกว่าสต็อกพร้อมจ่ายในคลัง!
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="pl-8 pt-4 flex gap-3 items-center">
                            <button type="submit" id="submit_btn" disabled
                                class="w-full bg-gradient-to-br from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-rose-500/20 transition-all hover:shadow-xl hover:-translate-y-0.5 flex justify-center items-center gap-2 cursor-pointer disabled:cursor-not-allowed disabled:shadow-none disabled:transform-none">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                ยืนยันการตัดจำนวน
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const barcodeInput = document.getElementById('barcode');
        const quantityInput = document.getElementById('quantity');
        const infoBox = document.getElementById('product_info_box');
        const placeholderBox = document.getElementById('product_placeholder');
        const stockWarning = document.getElementById('stock_warning');
        const submitBtn = document.getElementById('submit_btn');
        const loadingSpinner = document.getElementById('loading_spinner');
        let currentMaxStock = 0;

        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = this.value.trim();
                if (barcode) {
                    fetchProductData(barcode);
                }
            }
        });

        function fetchProductData(barcode) {
            placeholderBox.classList.add('hidden');
            infoBox.classList.remove('hidden');
            loadingSpinner.classList.remove('hidden');
            quantityInput.disabled = true;
            submitBtn.disabled = true;

            fetch(`/api/products/${barcode}`)
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.classList.add('hidden');

                    if (data.success) {
                        document.getElementById('display_name').innerText = data.product.name;
                        document.getElementById('display_stock').innerText = data.product.total_stock;
                        document.getElementById('product_id').value = data.product.id;
                        currentMaxStock = data.product.total_stock;
                        
                        quantityInput.disabled = false;
                        quantityInput.focus();
                        validateStock();
                    } else {
                        alert('❌ ไม่พบข้อมูลสินค้า: ' + barcode);
                        resetForm();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.classList.add('hidden');
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                    resetForm();
                });
        }

        function resetForm() {
            infoBox.classList.add('hidden');
            placeholderBox.classList.remove('hidden');
            barcodeInput.value = '';
            barcodeInput.focus();
            quantityInput.value = '';
            quantityInput.disabled = true;
            stockWarning.classList.add('hidden');
            submitBtn.disabled = true;
        }

        function validateStock() {
            const val = parseInt(quantityInput.value);
            
            // Allow empty but disable submit
            if (isNaN(val) || val <= 0) {
                stockWarning.classList.add('hidden');
                submitBtn.disabled = true;
                return;
            }

            if (val > currentMaxStock) {
                stockWarning.classList.remove('hidden');
                submitBtn.disabled = true;
            } else {
                stockWarning.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        quantityInput.addEventListener('input', validateStock);
    </script>
</x-app-layout>