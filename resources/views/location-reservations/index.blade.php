<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🔖 จองพื้นที่รอสินค้าเข้า</h2>
    </x-slot>

    {{-- Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-wrapper { position: relative; }
        .ts-control { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important; background: #f8fafc !important; }
        .ts-control:focus-within { border-color: #3b82f6 !important; box-shadow: 0 0 0 2px rgba(59,130,246,0.25) !important; background: #fff !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important; box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important; z-index: 9999 !important; position: absolute !important; background: #fff !important; }
        .ts-dropdown .option { padding: 0.4rem 0.75rem !important; }
        .ts-dropdown .option.active { background: #eff6ff !important; color: #1d4ed8 !important; }
    </style>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside space-y-0.5 text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ===== ฟอร์มสร้างการจอง ===== --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-sm">➕</span>
                            จองพื้นที่ใหม่
                        </h3>
                        <form method="POST" action="{{ route('location-reservations.store') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">ตำแหน่งที่ต้องการจอง <span class="text-red-500">*</span></label>
                                <select name="location_id" id="sel-location" required placeholder="ค้นหาตำแหน่ง...">
                                    <option value="">-- เลือกตำแหน่ง --</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }}{{ $loc->zone ? ' (Zone: '.$loc->zone.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">สินค้าที่รอ (ถ้าทราบ)</label>
                                <select name="product_id" id="sel-product" placeholder="ค้นหาสินค้า...">
                                    <option value="">-- ไม่ระบุ --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">จำนวนที่คาด <span class="text-red-500">*</span></label>
                                <input type="number" name="expected_qty" min="1" required value="{{ old('expected_qty', 1) }}"
                                    class="block w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">วันที่คาดว่าจะมาถึง</label>
                                <input type="datetime-local" name="expected_at" value="{{ old('expected_at') }}"
                                    class="block w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">หมายเหตุ</label>
                                <textarea name="note" rows="2" placeholder="รายละเอียดเพิ่มเติม..."
                                    class="block w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition resize-none">{{ old('note') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full flex justify-center items-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold text-white shadow-md transition-all hover:opacity-90"
                                style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                                🔖 ยืนยันจองพื้นที่
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ===== Tab: การจอง + ประวัติ ===== --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Tab buttons --}}
                    <div class="flex gap-2 bg-slate-100 p-1 rounded-xl w-fit">
                        <a href="{{ route('location-reservations.index', ['tab' => 'active']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $tab === 'active' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                            🔖 รอสินค้า
                            @if($pending->count() > 0)
                                <span class="ml-1.5 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pending->count() }}</span>
                            @endif
                        </a>
                        <a href="{{ route('location-reservations.index', ['tab' => 'history']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $tab === 'history' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                            📋 ประวัติ
                        </a>
                    </div>

                    {{-- TAB: Active (pending) --}}
                    @if($tab === 'active')
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-slate-800">การจองที่รออยู่</h3>
                            <span class="text-xs text-slate-400">{{ $pending->count() }} รายการ</span>
                        </div>

                        @if($pending->isEmpty())
                            <div class="text-center py-16 text-slate-400">
                                <div class="text-5xl mb-3">✅</div>
                                <p class="font-medium">ไม่มีการจองที่รออยู่</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-100">
                                @foreach($pending as $res)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-slate-800">{{ $res->location->name }}</span>
                                                @if($res->location->zone)
                                                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">Zone: {{ $res->location->zone }}</span>
                                                @endif
                                                <span class="text-xs px-2 py-0.5 rounded-full border font-semibold bg-purple-100 text-purple-800 border-purple-200">⏳ รอสินค้า</span>
                                            </div>
                                            <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-slate-500 mt-1.5">
                                                <span>📦 {{ $res->product ? $res->product->name.' ('.$res->product->sku.')' : 'ไม่ระบุสินค้า' }}</span>
                                                <span>🔢 คาด {{ number_format($res->expected_qty) }} ชิ้น</span>
                                                @if($res->expected_at)
                                                    <span>📅 {{ $res->expected_at->format('d/m/Y H:i') }}</span>
                                                @endif
                                                <span>👤 {{ $res->reserver->name }}</span>
                                            </div>
                                            @if($res->note)
                                                <p class="text-xs text-slate-400 italic mt-1">{{ $res->note }}</p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <form method="POST" action="{{ route('location-reservations.fulfill', $res->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs font-bold px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition"
                                                    onclick="return confirm('ยืนยันว่ารับสินค้าเข้าพื้นที่นี้แล้ว?')">
                                                    ✅ รับแล้ว
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('location-reservations.cancel', $res->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs font-bold px-3 py-1.5 bg-slate-200 hover:bg-red-100 hover:text-red-600 text-slate-600 rounded-lg transition"
                                                    onclick="return confirm('ยืนยันยกเลิกการจองนี้?')">
                                                    ยกเลิก
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="mt-1.5 text-xs text-slate-400">จองเมื่อ {{ $res->created_at->diffForHumans() }}</div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif

                    {{-- TAB: History (fulfilled + cancelled) --}}
                    @if($tab === 'history')
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-slate-800">ประวัติการจองพื้นที่</h3>
                            <span class="text-xs text-slate-400">{{ $history->total() }} รายการ</span>
                        </div>

                        @if($history->isEmpty())
                            <div class="text-center py-16 text-slate-400">
                                <div class="text-5xl mb-3">📋</div>
                                <p class="font-medium">ยังไม่มีประวัติ</p>
                            </div>
                        @else
                            {{-- Filter summary --}}
                            <div class="px-6 pt-4 pb-2 flex gap-3 text-xs">
                                <span class="flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-semibold">
                                    ✅ รับแล้ว {{ $history->getCollection()->where('status','fulfilled')->count() }} รายการ (หน้านี้)
                                </span>
                                <span class="flex items-center gap-1.5 bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full font-semibold">
                                    🚫 ยกเลิก {{ $history->getCollection()->where('status','cancelled')->count() }} รายการ (หน้านี้)
                                </span>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach($history as $res)
                                @php
                                    $isFulfilled = $res->status === 'fulfilled';
                                    $statusColor = $isFulfilled
                                        ? 'bg-green-100 text-green-800 border-green-200'
                                        : 'bg-slate-100 text-slate-500 border-slate-200';
                                    $statusLabel = $isFulfilled ? '✅ รับแล้ว' : '🚫 ยกเลิก';
                                @endphp
                                <div class="px-6 py-4 {{ !$isFulfilled ? 'opacity-60' : '' }}">
                                    <div class="flex items-start gap-4">
                                        {{-- Timeline dot --}}
                                        <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm {{ $isFulfilled ? 'bg-green-100' : 'bg-slate-100' }}">
                                            {{ $isFulfilled ? '✅' : '🚫' }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-slate-800">{{ $res->location->name }}</span>
                                                @if($res->location->zone)
                                                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">Zone: {{ $res->location->zone }}</span>
                                                @endif
                                                <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $statusColor }}">{{ $statusLabel }}</span>
                                            </div>
                                            <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-slate-500 mt-1.5">
                                                <span>📦 {{ $res->product ? $res->product->name.' ('.$res->product->sku.')' : 'ไม่ระบุสินค้า' }}</span>
                                                <span>🔢 {{ number_format($res->expected_qty) }} ชิ้น</span>
                                                @if($res->expected_at)
                                                    <span>📅 ETA: {{ $res->expected_at->format('d/m/Y') }}</span>
                                                @endif
                                                <span>👤 {{ $res->reserver->name }}</span>
                                            </div>
                                            @if($res->note)
                                                <p class="text-xs text-slate-400 italic mt-1">{{ $res->note }}</p>
                                            @endif
                                            <div class="mt-1.5 text-xs text-slate-400">
                                                จองเมื่อ {{ $res->created_at->format('d/m/Y H:i') }} ·
                                                {{ $isFulfilled ? 'รับเมื่อ' : 'ยกเลิกเมื่อ' }} {{ $res->updated_at->format('d/m/Y H:i') }}
                                                ({{ $res->updated_at->diffForHumans() }})
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            @if($history->hasPages())
                                <div class="px-6 py-4 border-t border-slate-100">
                                    {{ $history->appends(['tab' => 'history'])->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                    @endif

                </div>{{-- end col-span-2 --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#sel-location', { placeholder: 'ค้นหาตำแหน่ง...', maxOptions: 200 });
        new TomSelect('#sel-product',  { placeholder: 'ค้นหาสินค้า...', maxOptions: 200 });
    </script>

</x-app-layout>
