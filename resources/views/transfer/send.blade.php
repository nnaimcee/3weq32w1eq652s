<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                ย้ายสินค้า (Transfer)
            </h2>
        </div>
    </x-slot>

    {{-- Tom Select CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-wrapper { position: relative; }
        .ts-control { border-radius: 1rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important; background: #f8fafc !important; padding: 0.75rem 1rem !important; box-shadow: none !important; }
        .ts-control:focus-within { border-color: #818cf8 !important; box-shadow: 0 0 0 4px rgba(99,102,241,0.1) !important; background: #fff !important; }
        .ts-dropdown { border-radius: 1rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important;
                       box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1) !important; margin-top: 0.5rem !important;
                       z-index: 9999 !important; position: absolute !important; background: #fff !important; overflow: hidden !important; border: 1px solid #e2e8f0 !important; }
        .ts-dropdown .option { padding: 0.75rem 1rem !important; border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease; }
        .ts-dropdown .option.active { background: #f8fafc !important; color: #1e293b !important; }
        .ts-dropdown .option.selected { background: #eef2ff !important; color: #4f46e5 !important; }
        .option-sub { font-size: 0.75rem; color: #64748b; margin-top: 2px; font-weight: 500; }
        .form-card { overflow: visible !important; }
    </style>

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
                <div class="lg:w-1/3 bg-slate-50/50 border-b lg:border-b-0 lg:border-r border-slate-100 p-8 flex flex-col">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-indigo-200 mb-6">📦</div>
                    <h3 class="font-black text-xl text-slate-800 mb-3 tracking-tight">การย้ายสินค้าระหว่างตำแหน่ง</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8">
                        กระบวนการนี้จะทำการย้ายสต็อกสินค้าจาก <strong>ตำแหน่งต้นทาง</strong> ไปยัง <strong>ตำแหน่งปลายทาง</strong> โดยสินค้าจะเข้าสู่สถานะ <span class="bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded-md font-bold text-xs mx-1 border border-orange-200">🚚 ระหว่างทาง</span> (Transit) จนกว่าปลายทางจะกดยืนยันรับเข้า
                    </p>

                    <div class="space-y-4 mt-auto">
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
                             <div class="text-blue-500 text-xl font-bold mt-0.5">1</div>
                             <div>
                                 <p class="font-bold text-slate-700 text-sm">เลือกสินค้า</p>
                                 <p class="text-xs text-slate-500 font-medium mt-0.5">ระบุสินค้าและตำแหน่งที่จัดเก็บปัจจุบัน</p>
                             </div>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
                             <div class="text-emerald-500 text-xl font-bold mt-0.5">2</div>
                             <div>
                                 <p class="font-bold text-slate-700 text-sm">เลือกปลายทาง</p>
                                 <p class="text-xs text-slate-500 font-medium mt-0.5">ระบุตำแหน่งใหม่ที่ต้องการย้ายไป</p>
                             </div>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
                             <div class="text-amber-500 text-xl font-bold mt-0.5">3</div>
                             <div>
                                 <p class="font-bold text-slate-700 text-sm">ผู้รับกดยืนยัน</p>
                                 <p class="text-xs text-slate-500 font-medium mt-0.5">รอผู้รับปลายทางกดยืนยันการรับเข้า</p>
                             </div>
                        </div>
                    </div>
                </div>

                {{-- Right Pane: Form Inputs --}}
                <div class="lg:w-2/3 p-8">
                    <form action="{{ route('transfer.send') }}" method="POST" class="space-y-8 max-w-lg mx-auto py-4">
                        @csrf
                        <input type="hidden" name="location_id" id="location_id">

                        {{-- Step 1: Product --}}
                        <div class="relative pl-8">
                            <div class="absolute top-0 left-0 bottom-0 w-px bg-slate-100 flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shadow-sm ring-4 ring-white absolute -top-1 -left-[11px] border border-blue-200">1</div>
                            </div>
                            
                            <label class="block text-sm font-bold text-slate-800 mb-2">เลือกสินค้าและตำแหน่งต้นทาง</label>
                            <p class="text-xs text-slate-500 mb-3 font-medium">ค้นหาจากชื่อสินค้า หรือ รหัส SKU</p>
                            
                            <select name="product_id" id="product_select" required placeholder="พิมพ์ค้นหาสินค้า...">
                                <option value="">-- เลือกสินค้าที่ต้องการย้าย --</option>
                                @foreach($stocks as $st)
                                    <option value="{{ $st->product_id }}"
                                        data-location="{{ $st->location_id }}"
                                        data-label="{{ $st->product->name }}"
                                        data-sub="{{ $st->location->name }} — ว่าง {{ number_format($st->total_quantity) }} ชิ้น">
                                        {{ $st->product->name }} | {{ $st->location->name }} — ว่าง {{ number_format($st->total_quantity) }} ชิ้น
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Step 2: Destination --}}
                        <div class="relative pl-8">
                            <div class="absolute top-0 left-0 bottom-0 w-px bg-slate-100 flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs shadow-sm ring-4 ring-white absolute -top-1 -left-[11px] border border-emerald-200">2</div>
                            </div>
                            
                            <label class="block text-sm font-bold text-slate-800 mb-2">เลือกตำแหน่งปลายทาง</label>
                            <p class="text-xs text-slate-500 mb-3 font-medium">ระบุสถานที่ หรือ โซนใหม่ที่ต้องการนำสินค้าไปเก็บ</p>
                            
                            <select name="to_location_id" id="to_location_id" required placeholder="พิมพ์ค้นหาตำแหน่ง...">
                                <option value="">-- เลือกตำแหน่งปลายทาง --</option>
                                @foreach($destinations as $dest)
                                    <option value="{{ $dest->id }}"
                                        data-sub="{{ $dest->zone ? 'Zone: '.$dest->zone : '' }}">
                                        {{ $dest->name }}{{ $dest->zone ? ' (Zone: '.$dest->zone.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Step 3: Quantity --}}
                         <div class="relative pl-8">
                            <div class="absolute top-0 left-0 w-px bg-transparent flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xs shadow-sm ring-4 ring-white absolute -top-1 -left-[11px] border border-amber-200">3</div>
                            </div>
                            
                            <label class="block text-sm font-bold text-slate-800 mb-2">จำนวนที่จะย้าย</label>
                            <div class="relative mt-2 rounded-xl shadow-sm">
                                <input type="number" name="quantity"
                                    class="block w-full border border-slate-200 rounded-xl text-lg font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all shadow-inner"
                                    required min="1" placeholder="ระบุจำนวน...">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold">ชิ้น</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pl-8 pt-4 flex gap-3 items-center">
                             <button type="submit"
                                class="flex-1 bg-gradient-to-br from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-500/20 transition-all hover:shadow-xl hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                ยืนยันการย้าย
                            </button>

                            <a href="{{ route('transfer.pending') }}" class="py-3 px-5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold rounded-xl transition-colors text-sm flex items-center gap-2 shadow-sm bg-white whitespace-nowrap">
                                📋 <span class="hidden sm:inline">ดูรายการรอย้ายเข้า</span>
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tom Select JS --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        // ======== Searchable dropdown สินค้า ========
        new TomSelect('#product_select', {
            placeholder: 'พิมพ์ชื่อสินค้า, ตำแหน่ง...',
            searchField: ['text'],
            maxOptions: 200,
            render: {
                option: function(data, escape) {
                    const parts = data.text.split('|');
                    const name = parts[0] ? parts[0].trim() : data.text;
                    const sub  = parts[1] ? parts[1].trim() : '';
                    return `<div class="py-1.5 px-1">
                        <div class="font-bold text-slate-800 text-sm">${escape(name)}</div>
                        ${sub ? `<div class="option-sub mt-0.5">${escape(sub)}</div>` : ''}
                    </div>`;
                },
                item: function(data, escape) {
                    const parts = data.text.split('|');
                    return `<div class="font-bold text-slate-800">${escape(parts[0] ? parts[0].trim() : data.text)}</div>`;
                }
            },
            onChange: function(value) {
                // sync location_id hidden field
                const opt = this.options[value];
                if (opt) {
                    const el = document.querySelector(`#product_select option[value="${value}"]`);
                    document.getElementById('location_id').value = el ? el.getAttribute('data-location') : '';
                }
            }
        });

        // ======== Searchable dropdown ปลายทาง ========
        new TomSelect('#to_location_id', {
            placeholder: 'พิมพ์ชื่อตำแหน่ง, Zone...',
            searchField: ['text'],
            maxOptions: 200,
            render: {
                option: function(data, escape) {
                    const text = data.text;
                    // แยก zone ออกจากชื่อ (Zone: xxx)
                    const zoneMatch = text.match(/\(Zone:\s*([^)]+)\)/);
                    const zone = zoneMatch ? zoneMatch[1] : '';
                    const name = text.replace(/\s*\(Zone:[^)]+\)/, '').trim();
                    return `<div class="py-1.5 px-1">
                        <div class="font-bold text-slate-800 text-sm">${escape(name)}</div>
                        ${zone ? `<div class="option-sub px-1.5 py-0.5 bg-slate-100 rounded inline-block mt-1">Zone: <span class="font-bold text-slate-600">${escape(zone)}</span></div>` : ''}
                    </div>`;
                },
                item: function(data, escape) {
                    const name = data.text.replace(/\s*\(Zone:[^)]+\)/, '').trim();
                    return `<div class="font-bold text-slate-800">${escape(name)}</div>`;
                }
            }
        });
    </script>
</x-app-layout>