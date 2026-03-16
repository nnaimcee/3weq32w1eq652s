<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                เพิ่มสินค้าใหม่ (Add New Product)
            </h2>
        </div>
    </x-slot>

    <div class="py-6 w-full relative z-10 overflow-x-hidden">
        <!-- Abstract Background -->
        <div class="fixed top-0 right-0 w-full h-[500px] bg-gradient-to-bl from-blue-50/60 via-purple-50/30 to-transparent -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Left Column: Form --}}
                <div class="w-full lg:flex-1 space-y-6">
                    
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                            <div class="bg-emerald-100 rounded-lg p-1 text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                            <div>
                                <p class="font-bold text-sm">สำเร็จ!</p>
                                <p class="text-xs font-medium opacity-90">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                            <div class="bg-rose-100 rounded-lg p-1 text-rose-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                            <div>
                                <p class="font-bold text-sm">พบข้อผิดพลาด</p>
                                <ul class="list-disc list-inside space-y-0.5 mt-1 font-medium text-xs opacity-90">
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-6 sm:p-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>

                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl shadow-sm border border-blue-200">📦</div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight">ข้อมูลสินค้า</h3>
                                <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Product Information</p>
                            </div>
                        </div>

                        <form action="{{ route('products.store') }}" method="POST" id="productForm" class="space-y-6 relative z-10">
                            @csrf
                            
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">ชื่อสินค้า (Product Name) <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="p_name" required placeholder="ระบุชื่อสินค้า..."
                                    class="w-full border-slate-200 rounded-xl text-lg font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 focus:bg-white transition-all shadow-inner">
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">รหัสอ้างอิง (SKU) <span class="text-rose-500">*</span></label>
                                <input type="text" name="sku" id="p_sku" value="{{ $nextSku }}" required
                                    class="w-full border-slate-200 rounded-xl text-base font-mono font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 focus:bg-white transition-all shadow-inner">
                                <p class="text-[10px] font-bold text-slate-400 mt-2 flex items-center gap-1.5"><span class="text-blue-500">ℹ️</span> ระบบสร้างรหัสให้อัตโนมัติ สามารถแก้ไขได้</p>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <button type="submit"
                                    class="w-full bg-gradient-to-br from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl shadow-[0_8px_20px_-4px_rgba(37,99,235,0.4)] transition-all hover:shadow-[0_12px_25px_-4px_rgba(37,99,235,0.5)] hover:-translate-y-0.5 flex justify-center items-center gap-2 text-lg">
                                    💾 บันทึกสินค้าเข้าสู่ระบบ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right Column: Preview & Print (Sticky) --}}
                <div class="w-full lg:w-[400px] flex-shrink-0 lg:sticky lg:top-24 space-y-6">
                    
                    {{-- Barcode Preview --}}
                    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-6 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><span class="text-base">🖨️</span> ตัวอย่างสติกเกอร์ Barcode</h3>
                        </div>

                        <div class="bg-slate-100/50 rounded-xl p-4 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center min-h-[160px] relative">
                            {{-- Actual Sticker Size Container --}}
                            <div id="printableSticker" class="bg-white p-3 border shadow-sm text-center flex flex-col justify-between"
                                style="width: 50mm; height: 30mm;">
                                <div class="text-[10px] font-bold truncate leading-tight" id="preview_name">Product Name</div>
                                <div id="barcode_container" class="flex justify-center items-center opacity-70 my-1">
                                    {!! DNS1D::getBarcodeHTML('12345678', 'C128', 1.2, 30) !!}
                                </div>
                                <div class="text-[10px] font-mono leading-tight" id="preview_sku">{{ $nextSku }}</div>
                            </div>
                        </div>

                        <button type="button" onclick="printSticker()"
                            class="w-full mt-4 bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            พิมพ์สติกเกอร์ Barcode
                        </button>
                    </div>

                    {{-- QR Code Preview --}}
                    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-6 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><span class="text-base">📱</span> ตัวอย่างสติกเกอร์ QR Code</h3>
                        </div>

                        <div class="bg-indigo-50/50 rounded-xl p-4 border-2 border-dashed border-indigo-200/60 flex flex-col items-center justify-center min-h-[220px] relative">
                            {{-- Actual Sticker Size Container --}}
                            <div id="printableQrSticker" class="bg-white p-3 border shadow-sm text-center flex flex-col items-center justify-between"
                                style="width: 50mm; height: 50mm;">
                                <div class="text-[10px] font-bold w-full truncate leading-tight" id="preview_name_qr">Product Name</div>
                                <div id="qr_container" class="flex justify-center items-center opacity-80 my-1">
                                    {!! DNS2D::getBarcodeHTML('12345678', 'QRCODE', 3.5, 3.5) !!}
                                </div>
                                <div class="text-[10px] font-mono leading-tight" id="preview_sku_qr">{{ $nextSku }}</div>
                            </div>
                        </div>

                        <button type="button" onclick="printQrSticker()"
                            class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md shadow-indigo-600/20 transition-all hover:shadow-lg hover:shadow-indigo-600/30 hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            พิมพ์สติกเกอร์ QR Code
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Update Preview Real-time
        document.getElementById('p_name').addEventListener('input', e => {
            const val = e.target.value || 'Product Name';
            document.getElementById('preview_name').innerText = val;
            document.getElementById('preview_name_qr').innerText = val;
        });
        
        document.getElementById('p_sku').addEventListener('input', e => {
            const val = e.target.value || '-';
            document.getElementById('preview_sku').innerText = val;
            document.getElementById('preview_sku_qr').innerText = val;
        });

        function printSticker() {
            const printContent = document.getElementById('printableSticker').outerHTML;
            document.body.innerHTML = `
                <style>
                    @page { size: 50mm 30mm; margin: 0; }
                    body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #fff; }
                </style>
                ${printContent}
            `;
            window.print();
            location.reload();
        }

        function printQrSticker() {
            const printContent = document.getElementById('printableQrSticker').outerHTML;
            document.body.innerHTML = `
                <style>
                    @page { size: 50mm 50mm; margin: 0; }
                    body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #fff; }
                </style>
                ${printContent}
            `;
            window.print();
            location.reload();
        }
    </script>
</x-app-layout>
