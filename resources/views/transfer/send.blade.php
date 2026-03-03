<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🚚 ย้ายสินค้า</h2>
    </x-slot>

    {{-- Tom Select CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-wrapper { position: relative; }
        .ts-control { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important; background: #f8fafc !important; }
        .ts-control:focus-within { border-color: #3b82f6 !important; box-shadow: 0 0 0 2px rgba(59,130,246,0.25) !important; background: #fff !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important;
                       box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
                       z-index: 9999 !important; position: absolute !important; background: #fff !important; }
        .ts-dropdown .option { padding: 0.5rem 0.75rem !important; }
        .ts-dropdown .option.active { background: #eff6ff !important; color: #1d4ed8 !important; }
        .ts-dropdown .option.selected { background: #dbeafe !important; color: #1d4ed8 !important; }
        .option-sub { font-size: 0.72rem; color: #64748b; margin-top: 1px; }
        /* ไม่ให้ parent card clip dropdown */
        .form-card { overflow: visible !important; }
    </style>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-6 shadow-lg sm:rounded-2xl border-t-4 border-blue-500">
                <form action="{{ route('transfer.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="location_id" id="location_id">

                    {{-- เลือกสินค้า --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            📦 เลือกสินค้าและตำแหน่งต้นทาง
                            <span class="text-xs font-normal text-slate-400 ml-1">— พิมพ์เพื่อค้นหา</span>
                        </label>
                        <select name="product_id" id="product_select" required placeholder="ค้นหาสินค้า...">
                            <option value="">-- เลือกสินค้าที่ต้องการย้าย --</option>
                            @foreach($stocks as $st)
                                <option value="{{ $st->product_id }}"
                                    data-location="{{ $st->location_id }}"
                                    data-label="{{ $st->product->name }}"
                                    data-sub="{{ $st->location->name }} — {{ number_format($st->total_quantity) }} ชิ้น">
                                    {{ $st->product->name }} | {{ $st->location->name }} — {{ number_format($st->total_quantity) }} ชิ้น
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- เลือกปลายทาง --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            📍 เลือกตำแหน่งปลายทาง
                            <span class="text-xs font-normal text-slate-400 ml-1">— พิมพ์เพื่อค้นหา</span>
                        </label>
                        <select name="to_location_id" id="to_location_id" required placeholder="ค้นหาตำแหน่ง...">
                            <option value="">-- เลือกตำแหน่งปลายทาง --</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}"
                                    data-sub="{{ $dest->zone ? 'Zone: '.$dest->zone : '' }}">
                                    {{ $dest->name }}{{ $dest->zone ? ' (Zone: '.$dest->zone.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- จำนวน --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">🔢 จำนวนที่จะย้าย</label>
                        <input type="number" name="quantity"
                            class="block w-full border border-slate-200 rounded-xl text-sm px-4 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
                            required min="1" placeholder="ระบุจำนวน">
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90 hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        ส่งสินค้าไปยังปลายทาง
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('transfer.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        📋 ดูรายการค้างอยู่ใน Transit →
                    </a>
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
                        <div class="font-semibold text-slate-800">${escape(name)}</div>
                        ${sub ? `<div class="option-sub">${escape(sub)}</div>` : ''}
                    </div>`;
                },
                item: function(data, escape) {
                    const parts = data.text.split('|');
                    return `<div>${escape(parts[0] ? parts[0].trim() : data.text)}</div>`;
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
                        <div class="font-semibold text-slate-800">${escape(name)}</div>
                        ${zone ? `<div class="option-sub">Zone: ${escape(zone)}</div>` : ''}
                    </div>`;
                },
                item: function(data, escape) {
                    const name = data.text.replace(/\s*\(Zone:[^)]+\)/, '').trim();
                    return `<div>${escape(name)}</div>`;
                }
            }
        });
    </script>

</x-app-layout>