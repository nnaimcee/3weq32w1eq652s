<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                จองพื้นที่รอสินค้าเข้า (Location Reservations)
            </h2>
        </div>
    </x-slot>

    {{-- Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-wrapper { position: relative; }
        .ts-control { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important; background: #f8fafc !important; font-weight: 600 !important; color: #334155 !important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05) !important; padding: 0.75rem 1rem !important; }
        .ts-control:focus-within { border-color: #818cf8 !important; box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.1) !important; background: #ffffff !important; }
        .ts-dropdown { border-radius: 0.75rem !important; border-color: #e2e8f0 !important; font-size: 0.875rem !important; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1) !important; z-index: 9999 !important; position: absolute !important; background: #ffffff !important; margin-top: 0.5rem !important; overflow: hidden !important; }
        .ts-dropdown .option { padding: 0.6rem 1rem !important; font-weight: 500 !important; color: #475569 !important; transition: all 0.2s; }
        .ts-dropdown .option.active { background: #eef2ff !important; color: #4f46e5 !important; font-weight: 700 !important; }
    </style>

    <div class="py-6 w-full relative z-10 overflow-x-hidden">
        <!-- Abstract Background -->
        <div class="fixed top-0 left-0 w-full h-[500px] bg-gradient-to-b from-purple-50/60 via-blue-50/30 to-transparent -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-0 lg:flex lg:gap-8 items-start">

            {{-- Flash Messages --}}
            <div class="lg:hidden w-full mb-6">
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
            </div>

            {{-- Left Column: Form (Sticky on Desktop) --}}
            <div class="w-full lg:w-[380px] flex-shrink-0 lg:sticky lg:top-24 space-y-6">
                
                {{-- Flash Desktop --}}
                <div class="hidden lg:block w-full">
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl shadow-sm flex items-start gap-3 mb-6">
                            <div class="bg-emerald-100 rounded-md p-1 text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                            <p class="text-sm font-bold">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl shadow-sm flex items-start gap-3 mb-6">
                            <div class="bg-rose-100 rounded-md p-1 text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                            <ul class="list-disc list-inside space-y-0.5 font-medium text-xs">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Form Card --}}
                <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-6 sm:p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-sm border border-purple-200 mb-3">🔖</div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">สร้างการจองพื้นที่</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">กันพื้นที่ไว้สำหรับสินค้าที่กำลังจะเข้า</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('location-reservations.store') }}" class="space-y-6">
                        @csrf

                        <!-- Location Select -->
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">ตำแหน่งที่ต้องการจอง <span class="text-rose-500">*</span></label>
                            <select name="location_id" id="sel-location" required placeholder="ค้นหาตำแหน่ง...">
                                <option value="">-- เลือกตำแหน่ง --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }}{{ $loc->zone ? ' (Zone: '.$loc->zone.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Select -->
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">สินค้าที่รอ (ระบุหรือไม่ก็ได้)</label>
                            <select name="product_id" id="sel-product" placeholder="ค้นหาสินค้า...">
                                <option value="">-- ไม่รบุ --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Expected Qty -->
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">จำนวน (ชิ้น) <span class="text-rose-500">*</span></label>
                                <input type="number" name="expected_qty" min="1" required value="{{ old('expected_qty', 1) }}"
                                    class="block w-full border border-slate-200 rounded-xl text-base font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-400 focus:bg-white transition-all shadow-inner text-center">
                            </div>

                            <!-- Expected Date -->
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">วันที่สินค้าเข้า</label>
                                <input type="datetime-local" name="expected_at" value="{{ old('expected_at') }}"
                                    class="block w-full border border-slate-200 rounded-xl text-sm font-semibold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-400 focus:bg-white transition-all shadow-inner">
                            </div>
                        </div>

                        <!-- Note -->
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-widest">หมายเหตุ</label>
                            <textarea name="note" rows="2" placeholder="รายละเอียดพิ่มเติม..."
                                class="block w-full border border-slate-200 rounded-xl text-sm font-semibold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-400 focus:bg-white transition-all shadow-inner resize-none">{{ old('note') }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-br from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-[0_8px_20px_-4px_rgba(147,51,234,0.4)] transition-all hover:shadow-[0_12px_25px_-4px_rgba(147,51,234,0.5)] hover:-translate-y-0.5 flex justify-center items-center gap-2">
                            🔖 ยืนยันการจองพื้นที่
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: Lists --}}
            <div class="w-full flex-1">
                
                {{-- Modern Tab Navigation --}}
                <div class="flex items-center p-1.5 bg-slate-100/80 backdrop-blur-md rounded-2xl w-max mb-6 border border-slate-200/60 shadow-inner">
                    <a href="{{ route('location-reservations.index', ['tab' => 'active']) }}"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all relative flex items-center gap-2 {{ $tab === 'active' ? 'bg-white text-purple-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50 border border-transparent' }}">
                        <span>รอเข้าคลัง</span>
                        @if($pending->count() > 0)
                            <span class="{{ $tab === 'active' ? 'bg-purple-100 text-purple-700' : 'bg-slate-200 text-slate-500' }} text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pending->count() }}</span>
                        @endif
                    </a>
                    <a href="{{ route('location-reservations.index', ['tab' => 'history']) }}"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all relative flex items-center gap-2 {{ $tab === 'history' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50 border border-transparent' }}">
                        <span>ประวัติการจอง</span>
                    </a>
                </div>

                {{-- TAB CONTENT: Active (pending) --}}
                @if($tab === 'active')
                <div class="bg-white rounded-[2rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden relative">
                    <div class="px-6 sm:px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">พื้นที่รอนำสินค้าเก็บเข้า</h3>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">รายการทั้งหมดที่ยังไม่ได้รับสินค้าจริง</p>
                        </div>
                        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1 rounded-full">{{ $pending->count() }} รายการ</span>
                    </div>

                    @if($pending->isEmpty())
                        <div class="text-center py-20 px-6">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner border border-slate-100">✨</div>
                            <h4 class="text-lg font-bold text-slate-800">ไม่มีการจองพื้นที่ที่รออยู่</h4>
                            <p class="text-sm font-medium text-slate-500 mt-1">คลังสัมผัสกับความว่างเปล่า... เริ่มจองพื้นที่ใหม่จากฟอร์มด้ายซ้ายได้เลย!</p>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach($pending as $res)
                            <div class="px-6 sm:px-8 py-5 hover:bg-slate-50/50 transition-colors group">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2.5 flex-wrap mb-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 text-xs font-black shadow-sm border border-indigo-100">📍</span>
                                            <span class="font-black text-lg text-slate-800 tracking-tight">{{ $res->location->name }}</span>
                                            @if($res->location->zone)
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-widest">Zone {{ $res->location->zone }}</span>
                                            @endif
                                            <span class="text-[10px] px-2 py-0.5 rounded border font-bold uppercase tracking-widest bg-purple-50 text-purple-700 border-purple-200">⏳ รอรับเข้า</span>
                                        </div>
                                        
                                        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm mt-3 relative overflow-hidden">
                                            <div class="absolute top-0 right-0 w-16 h-16 bg-slate-50 rounded-bl-full -z-10"></div>
                                            <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-slate-600">
                                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                                    <span class="text-slate-400">📦 สินค้า:</span> 
                                                    <span class="font-bold text-slate-800">{{ $res->product ? $res->product->name.' ('.$res->product->sku.')' : 'ไม่ระบุสินค้า' }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-slate-400">จำนวน:</span> 
                                                    <span class="font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg border border-blue-100">{{ number_format($res->expected_qty) }} ชิ้น</span>
                                                </div>
                                                @if($res->expected_at)
                                                    <div class="flex items-center gap-2 text-slate-500">
                                                        <span class="text-slate-400">📅 กำหนด:</span> 
                                                        <span>{{ $res->expected_at->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 mt-4">
                                            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 bg-slate-100/50 px-2.5 py-1 rounded-lg border border-slate-200/50">
                                                <div class="w-4 h-4 rounded-full bg-slate-200 flex items-center justify-center text-[8px] text-slate-500">👤</div>
                                                {{ $res->reserver->name }}
                                            </div>
                                            <div class="text-[10px] font-bold text-slate-400">
                                                สร้างเมื่อ {{ $res->created_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        @if($res->note)
                                            <p class="text-xs font-medium text-slate-500 italic mt-3 bg-yellow-50 text-yellow-800 p-2.5 rounded-lg border border-yellow-200/50 inline-block">📝 "{{ $res->note }}"</p>
                                        @endif
                                    </div>

                                    <div class="flex flex-row sm:flex-col items-center sm:items-end gap-2 flex-shrink-0 border-t border-slate-100 sm:border-t-0 pt-4 sm:pt-0 mt-4 sm:mt-0 w-full sm:w-auto overflow-hidden">
                                        <form method="POST" action="{{ route('location-reservations.fulfill', $res->id) }}" class="w-full sm:w-auto relative mb-1">
                                            @csrf
                                            <button type="submit"
                                                class="w-full sm:w-32 text-sm font-bold py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-all shadow-[0_4px_10px_-2px_rgba(16,185,129,0.3)] hover:shadow-[0_6px_15px_-2px_rgba(16,185,129,0.4)] hover:-translate-y-0.5 flex justify-center items-center gap-1.5"
                                                onclick="return confirm('ยืนยันว่ารับสินค้าตามจำนวนเข้าพื้นที่นี้แล้ว?')">
                                                ✅ รับสินค้า
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('location-reservations.cancel', $res->id) }}" class="w-full sm:w-auto">
                                            @csrf
                                            <button type="submit"
                                                class="w-full sm:w-32 text-xs font-bold py-2 bg-white border border-slate-200 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 text-slate-500 rounded-xl transition-colors flex justify-center items-center gap-1.5"
                                                onclick="return confirm('ยืนยันยกเลิกการจองพื้นที่นี้?')">
                                                ยกเลิกการจอง
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif

                {{-- TAB CONTENT: History (fulfilled + cancelled) --}}
                @if($tab === 'history')
                <div class="bg-white rounded-[2rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden">
                    <div class="px-6 sm:px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">ประวัติการกระทำทั้งหมด</h3>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">รวมการรับสำเร็จและการยกเลิก</p>
                        </div>
                        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1 rounded-full">{{ $history->total() }} รายการ</span>
                    </div>

                    @if($history->isEmpty())
                        <div class="text-center py-20 px-6">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner border border-slate-100">📋</div>
                            <h4 class="text-lg font-bold text-slate-800">ประวัติยังว่างเปล่า</h4>
                            <p class="text-sm font-medium text-slate-500 mt-1">จะเริ่มบันทึกประวัติทันทีที่คุณรับสินค้าหรือยกเลิกการจอง</p>
                        </div>
                    @else
                        {{-- Filter summary --}}
                        <div class="px-6 sm:px-8 py-3 bg-slate-50 border-b border-slate-100 flex gap-3 text-[11px] uppercase tracking-widest font-black">
                            <span class="flex items-center gap-1.5 text-emerald-700">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                รับแล้ว {{ $history->getCollection()->where('status','fulfilled')->count() }} (หน้านี้)
                            </span>
                            <span class="flex items-center gap-1.5 text-rose-700 ml-4">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                ยกเลิก {{ $history->getCollection()->where('status','cancelled')->count() }} (หน้านี้)
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($history as $res)
                            @php
                                $isFulfilled = $res->status === 'fulfilled';
                                $statusColor = $isFulfilled
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    : 'bg-rose-50 text-rose-600 border-rose-200';
                                $statusLabel = $isFulfilled ? 'รับเข้าแล้ว' : 'ยกเลิกแล้ว';
                                $dotColor = $isFulfilled ? 'bg-emerald-100 text-emerald-600 ring-4 ring-emerald-50' : 'bg-rose-100 text-rose-600 ring-4 ring-rose-50';
                                $icon = $isFulfilled ? '✅' : '🚫';
                            @endphp
                            
                            <div class="px-6 sm:px-8 py-5 hover:bg-slate-50/50 transition-colors {{ !$isFulfilled ? 'opacity-70 bg-slate-50/30' : '' }}">
                                <div class="flex items-start gap-4">
                                    {{-- Timeline dot --}}
                                    <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-[10px] {{ $dotColor }} font-bold shadow-sm">
                                        {{ $icon }}
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <span class="font-bold text-slate-800 text-base">{{ $res->location->name }}</span>
                                            @if($res->location->zone)
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-widest">Zone {{ $res->location->zone }}</span>
                                            @endif
                                            <span class="text-[10px] px-2 py-0.5 rounded-md border font-bold uppercase tracking-widest {{ $statusColor }}">{{ $statusLabel }}</span>
                                        </div>
                                        
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-medium text-slate-600 mb-2">
                                            <span class="bg-white px-2 py-1 rounded border border-slate-200 shadow-sm text-xs">📦 {{ $res->product ? $res->product->name.' ('.$res->product->sku.')' : 'ไม่ระบุสินค้า' }}</span>
                                            <span class="text-xs">จำนวน: <span class="font-bold text-slate-800">{{ number_format($res->expected_qty) }}</span></span>
                                            <span class="text-xs text-slate-400">โดย: {{ $res->reserver->name }}</span>
                                        </div>
                                        
                                        @if($res->note)
                                            <p class="text-xs text-slate-500 italic mt-1.5 mb-2 px-3 py-2 bg-white rounded-lg border border-slate-100">"{{ $res->note }}"</p>
                                        @endif
                                        
                                        <div class="mt-2 text-[11px] font-bold text-slate-400 flex items-center gap-2 uppercase tracking-wide">
                                            <span>สร้าง: <span class="text-slate-500 font-medium lowercase">{{ $res->created_at->format('d M Y, H:i') }}</span></span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span>อัปเดต: <span class="text-slate-500 font-medium lowercase">{{ $res->updated_at->format('d M Y, H:i') }} ({{ $res->updated_at->diffForHumans() }})</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if($history->hasPages())
                            <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-[2rem]">
                                {{ $history->appends(['tab' => 'history'])->links() }}
                            </div>
                        @endif
                    @endif
                </div>
                @endif

            </div>{{-- end right column --}}
        </div>
    </div>

    <!-- Tom Select initialization -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect('#sel-location', { 
                placeholder: 'ค้นหาตำแหน่ง...', 
                maxOptions: 200,
                render: {
                    option: function(data, escape) {
                        return '<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-slate-300"></span>' + escape(data.text) + '</div>';
                    },
                    item: function(data, escape) {
                        return '<div class="font-bold flex items-center gap-2 shadow-sm"><span class="w-3 h-3 rounded-md bg-indigo-100 text-indigo-600 flex items-center justify-center text-[8px] border border-indigo-200 border-solid truncate">📍</span>' + escape(data.text) + '</div>';
                    }
                }
            });
            new TomSelect('#sel-product', { 
                placeholder: 'ค้นหาสินค้า...', 
                maxOptions: 200,
                render: {
                    option: function(data, escape) {
                        return '<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-slate-300"></span>' + escape(data.text) + '</div>';
                    },
                    item: function(data, escape) {
                        return '<div class="font-bold flex items-center gap-2 shadow-sm"><span class="w-3 h-3 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center text-[8px] border border-blue-200 border-solid truncate">📦</span>' + escape(data.text) + '</div>';
                    }
                }
            });
        });
    </script>
</x-app-layout>
