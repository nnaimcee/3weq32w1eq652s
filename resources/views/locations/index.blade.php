<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                จัดการสถานที่จัดเก็บ (Locations)
            </h2>
        </div>
    </x-slot>

    <div class="py-6 w-full relative z-10 overflow-x-hidden">
        <!-- Abstract Background -->
        <div class="fixed top-0 left-0 w-full h-[500px] bg-gradient-to-br from-indigo-50/60 via-blue-50/30 to-purple-50/20 -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-0 lg:flex lg:gap-8 items-start">

            {{-- Flash Messages (Mobile) --}}
            <div class="lg:hidden w-full mb-6">
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                        <div class="bg-emerald-100 rounded-lg p-1 text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                        <div>
                            <p class="font-bold text-sm">สำเร็จ!</p>
                            <p class="text-xs font-medium opacity-90">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-sm flex items-start gap-3">
                        <div class="bg-rose-100 rounded-lg p-1 text-rose-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <div>
                            <p class="font-bold text-sm">พบข้อผิดพลาด</p>
                            <ul class="list-disc list-inside space-y-0.5 mt-1 font-medium text-xs opacity-90">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Left Column: Form เพิ่มสถานที่ใหม่ (Sticky on Desktop) --}}
            <div class="w-full lg:w-[360px] flex-shrink-0 lg:sticky lg:top-24 space-y-6">
                
                {{-- Flash Messages (Desktop) --}}
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

                <div class="bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white rounded-[2rem] p-6 lg:p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>
                    
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-sm border border-indigo-200">➕</div>
                        <div>
                            <h3 class="font-black text-lg text-slate-800 tracking-tight">เพิ่มสถานที่ใหม่</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Create Location</p>
                        </div>
                    </div>

                    <form action="{{ route('locations.store') }}" method="POST" class="space-y-4 relative z-10">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-widest">ชื่อสถานที่ <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="เช่น Z1-S1-B01"
                                class="w-full border-slate-200 rounded-xl text-sm font-semibold px-4 py-2.5 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-widest">โซน (Zone)</label>
                            <input type="text" name="zone" placeholder="เช่น A, B, Cold"
                                class="w-full border-slate-200 rounded-xl text-sm font-semibold px-4 py-2.5 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-widest">ชั้น (Shelf)</label>
                                <input type="text" name="shelf" placeholder="เช่น S1"
                                    class="w-full border-slate-200 rounded-xl text-sm font-semibold px-4 py-2.5 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-widest">ช่อง (Bin)</label>
                                <input type="text" name="bin" placeholder="เช่น B01"
                                    class="w-full border-slate-200 rounded-xl text-sm font-semibold px-4 py-2.5 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-widest">ประเภท <span class="text-rose-500">*</span></label>
                            <select name="type" required
                                class="w-full border-slate-200 rounded-xl text-sm font-semibold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner">
                                <option value="storage">📦 Storage (จัดเก็บ)</option>
                                <option value="transit">🚚 Transit (พักสินค้า)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-widest">ความจุ (Capacity) <span class="text-slate-400 opacity-80 font-medium normal-case">(ชิ้น)</span></label>
                            <input type="number" name="capacity" min="1" value="5000" placeholder="5000"
                                class="w-full border-slate-200 rounded-xl text-base font-bold px-4 py-3 bg-slate-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 focus:bg-white transition-all shadow-inner text-center">
                        </div>
                        <button type="submit"
                            class="w-full mt-2 bg-gradient-to-br from-indigo-600 to-blue-700 hover:from-indigo-700 hover:to-blue-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-[0_8px_20px_-4px_rgba(79,70,229,0.4)] transition-all hover:shadow-[0_12px_25px_-4px_rgba(79,70,229,0.5)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            💾 บันทึกสถานที่
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: ตารางรายการสถานที่ --}}
            <div class="w-full flex-1">
                {{-- Search Bar --}}
                <div class="mb-6 bg-white/60 backdrop-blur-xl p-2 rounded-[1.5rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white">
                    <form action="{{ route('locations.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-500 z-10">
                                <span class="text-slate-400 text-lg">🔍</span>
                            </div>
                            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อสถานที่ หรือ Zone..." 
                                onkeyup="filterLocations()"
                                class="w-full pl-12 pr-4 py-3.5 bg-white/80 border-0 rounded-xl focus:ring-0 focus:bg-white text-sm font-semibold transition-all focus:outline-none shadow-inner text-slate-700 placeholder-slate-400">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="hidden sm:flex items-center justify-center bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 px-8 rounded-xl shadow-md transition-all text-sm">
                                ค้นหา
                            </button>
                            @if(request('search'))
                                <a href="{{ route('locations.index') }}" class="bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-700 font-bold py-3.5 px-6 rounded-xl shadow-sm border border-slate-200 transition-all flex items-center justify-center text-sm w-full sm:w-auto">
                                    ล้าง
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="bg-white/90 backdrop-blur-xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white rounded-[2rem] overflow-hidden relative">
                    <div class="px-6 py-5 bg-white/50 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-black text-slate-800 tracking-tight text-lg">รายการสถานที่ทั้งหมด</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $locations->total() }} Locations</p>
                        </div>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="sm:hidden divide-y divide-slate-100" id="locationsCardList">
                        @forelse ($locations as $location)
                            <div class="location-row p-5 group" id="card-{{ $location->id }}" data-search="{{ mb_strtolower($location->name . ' ' . $location->zone . ' ' . $location->shelf . ' ' . $location->bin) }}">
                                {{-- Display Mode --}}
                                <div class="display-card-{{ $location->id }}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <p class="font-mono font-black text-slate-800 text-xl tracking-tight leading-none">{{ $location->name }}</p>
                                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2 py-0.5 rounded font-bold text-[10px] uppercase tracking-widest">Zone {{ $location->zone ?? '-' }}</span>
                                                @if($location->type === 'storage')
                                                    <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded-md font-black text-[10px] uppercase tracking-widest flex items-center gap-1"><span class="text-[8px] opacity-70">📦</span> Storage</span>
                                                @else
                                                    <span class="bg-orange-50 border border-orange-100 text-orange-700 px-2 py-0.5 rounded-md font-black text-[10px] uppercase tracking-widest flex items-center gap-1"><span class="text-[8px] opacity-70">🚚</span> Transit</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right flex flex-col items-end gap-1.5">
                                            @if($location->status === 'active')
                                                <span class="bg-green-50 border border-green-100 text-green-700 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest">✅ Active</span>
                                            @elseif($location->status === 'full')
                                                <span class="bg-amber-50 border border-amber-100 text-amber-700 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest">📦 Full</span>
                                            @else
                                                <span class="bg-rose-50 border border-rose-100 text-rose-700 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest">❌ Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-3 flex justify-between items-center mb-4">
                                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">ความจุ / สินค้า</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="font-black text-blue-600 text-lg leading-none">{{ number_format($location->stocks_sum_quantity ?? 0) }}</span>
                                            <span class="text-slate-400 text-[10px] font-bold">/ {{ number_format($location->capacity ?? 5000) }} ชิ้น</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 mt-4">
                                        <button onclick="showEditFormCard({{ $location->id }})"
                                            class="w-full flex items-center justify-center gap-1.5 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-bold py-2.5 rounded-xl transition-all shadow-sm">
                                            ✏️ แก้ไข
                                        </button>
                                        <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                            onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสถานที่นี้?');" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full flex items-center justify-center gap-1.5 bg-white border border-rose-200 hover:bg-rose-50 hover:border-rose-300 text-rose-600 text-sm font-bold py-2.5 rounded-xl transition-all shadow-sm">
                                                🗑️ ลบ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                {{-- Edit Mode (Mobile) --}}
                                <div class="hidden edit-card-{{ $location->id }} bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    <form action="{{ route('locations.update', $location->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="col-span-2">
                                                <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">ชื่อสถานที่</label>
                                                <input type="text" name="name" value="{{ $location->name }}" required class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1">
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">Zone</label>
                                                <input type="text" name="zone" value="{{ $location->zone }}" class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1">
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">Shelf</label>
                                                <input type="text" name="shelf" value="{{ $location->shelf }}" class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1">
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">Bin</label>
                                                <input type="text" name="bin" value="{{ $location->bin }}" class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1">
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">ความจุ</label>
                                                <input type="number" name="capacity" value="{{ $location->capacity }}" class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1 text-center font-mono">
                                            </div>
                                            <div class="col-span-2 grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">ประเภท</label>
                                                    <select name="type" class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1">
                                                        <option value="storage" {{ $location->type === 'storage' ? 'selected' : '' }}>Storage</option>
                                                        <option value="transit" {{ $location->type === 'transit' ? 'selected' : '' }}>Transit</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">สถานะ</label>
                                                    <select name="status" class="w-full border-slate-200 rounded-lg text-sm font-semibold bg-white mt-1">
                                                        <option value="active" {{ $location->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $location->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="full" {{ $location->status === 'full' ? 'selected' : '' }}>Full</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 mt-4">
                                            <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold py-2.5 rounded-lg shadow-sm transition-colors">บันทึกข้อมูล</button>
                                            <button type="button" onclick="hideEditFormCard({{ $location->id }})" class="flex-1 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-bold py-2.5 rounded-lg shadow-sm transition-colors">ยกเลิก</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="text-4xl mb-3">📍</div>
                                <h4 class="text-base font-bold text-slate-800">ยังไม่มีสถานที่ในระบบ</h4>
                                <p class="text-sm text-slate-500 font-medium">เพิ่มสถานที่คลังสินค้าจากฟอร์มด้านบน</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden sm:block overflow-x-auto relative">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead class="bg-slate-50/80 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">ข้อมูลสถานที่</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">ประเภท</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">สถานะ</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">ความจุ / สินค้า (ชิ้น)</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="locationsTableBody">
                                @forelse ($locations as $location)
                                    <tr class="hover:bg-indigo-50/30 transition-colors location-row group" id="row-{{ $location->id }}" data-search="{{ mb_strtolower($location->name . ' ' . $location->zone . ' ' . $location->shelf . ' ' . $location->bin) }}">
                                        {{-- Display Mode --}}
                                        <td class="px-6 py-4 display-cell-{{ $location->id }}">
                                            <div class="font-mono font-black text-slate-800 text-lg tracking-tight leading-none mb-1">{{ $location->name }}</div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-bold uppercase tracking-widest">Zone {{ $location->zone ?? '-' }}</span>
                                                <span class="text-[10px] font-semibold text-slate-500">S: {{ $location->shelf ?? '-' }}, B: {{ $location->bin ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center display-cell-{{ $location->id }}">
                                            @if($location->type === 'storage')
                                                <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded-md font-black text-[10px] uppercase tracking-widest inline-flex items-center gap-1"><span class="text-[8px] opacity-70">📦</span> Storage</span>
                                            @else
                                                <span class="bg-orange-50 border border-orange-100 text-orange-700 px-2 py-0.5 rounded-md font-black text-[10px] uppercase tracking-widest inline-flex items-center gap-1"><span class="text-[8px] opacity-70">🚚</span> Transit</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center display-cell-{{ $location->id }}">
                                            @if($location->status === 'active')
                                                <span class="bg-green-50 border border-green-100 text-green-700 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest">✅ Active</span>
                                            @elseif($location->status === 'full')
                                                <span class="bg-amber-50 border border-amber-100 text-amber-700 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest">📦 Full</span>
                                            @else
                                                <span class="bg-rose-50 border border-rose-100 text-rose-700 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest">❌ Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center display-cell-{{ $location->id }}">
                                            <div class="flex items-baseline justify-center gap-1">
                                                <span class="font-black text-blue-600 text-xl leading-none">{{ number_format($location->stocks_sum_quantity ?? 0) }}</span>
                                                <span class="text-slate-400 text-xs font-bold">/ {{ number_format($location->capacity ?? 5000) }}</span>
                                            </div>
                                            <div class="w-full max-w-[120px] mx-auto bg-slate-100 rounded-full h-1 mt-2 overflow-hidden shadow-inner">
                                                @php
                                                    $capPct = ($location->capacity ?? 5000) > 0 ? min(100, (($location->stocks_sum_quantity ?? 0) / ($location->capacity ?? 5000)) * 100) : 0;
                                                    $bgCol = $capPct >= 100 ? 'bg-rose-500' : ($capPct > 80 ? 'bg-amber-400' : 'bg-emerald-400');
                                                @endphp
                                                <div class="{{ $bgCol }} h-1 rounded-full" style="width: {{ $capPct }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right display-cell-{{ $location->id }}">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button onclick="showEditForm({{ $location->id }})" title="แก้ไข"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 flex items-center justify-center shadow-sm transition-all hover:-translate-y-0.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                                    onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสถานที่นี้?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="ลบ"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 flex items-center justify-center shadow-sm transition-all hover:-translate-y-0.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        {{-- Edit Mode (Desktop) --}}
                                        <td colspan="5" class="hidden edit-cell-{{ $location->id }} px-6 py-4 bg-slate-50 border-y border-slate-200 shadow-inner">
                                            <form action="{{ route('locations.update', $location->id) }}" method="POST"
                                                class="flex flex-wrap items-end gap-3 justify-between">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex flex-wrap gap-3 flex-1">
                                                    <div class="w-32 min-w-[120px]">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">ชื่อ</label>
                                                        <input type="text" name="name" value="{{ $location->name }}" required
                                                            class="w-full border-slate-300 rounded-lg text-sm font-semibold mt-1">
                                                    </div>
                                                    <div class="w-20">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">Zone</label>
                                                        <input type="text" name="zone" value="{{ $location->zone }}"
                                                            class="w-full border-slate-300 rounded-lg text-sm font-semibold mt-1">
                                                    </div>
                                                    <div class="w-16">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">Shelf</label>
                                                        <input type="text" name="shelf" value="{{ $location->shelf }}"
                                                            class="w-full border-slate-300 rounded-lg text-sm font-semibold mt-1">
                                                    </div>
                                                    <div class="w-16">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">Bin</label>
                                                        <input type="text" name="bin" value="{{ $location->bin }}"
                                                            class="w-full border-slate-300 rounded-lg text-sm font-semibold mt-1">
                                                    </div>
                                                    <div class="w-28">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">จุ (ชิ้น)</label>
                                                        <input type="number" name="capacity" min="1" value="{{ $location->capacity ?? 5000 }}"
                                                            class="w-full border-slate-300 rounded-lg text-sm font-mono text-center font-bold mt-1">
                                                    </div>
                                                    <div class="w-24">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">ประเภท</label>
                                                        <select name="type" class="w-full border-slate-300 rounded-lg text-sm font-semibold mt-1">
                                                            <option value="storage" {{ $location->type === 'storage' ? 'selected' : '' }}>Storage</option>
                                                            <option value="transit" {{ $location->type === 'transit' ? 'selected' : '' }}>Transit</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-24">
                                                        <label class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">สถานะ</label>
                                                        <select name="status" class="w-full border-slate-300 rounded-lg text-sm font-semibold mt-1">
                                                            <option value="active" {{ $location->status === 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $location->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            <option value="full" {{ $location->status === 'full' ? 'selected' : '' }}>Full</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="flex gap-2 shrink-0">
                                                    <button type="submit"
                                                        class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                                                        <span>💾</span> บันทึก
                                                    </button>
                                                    <button type="button" onclick="hideEditForm({{ $location->id }})"
                                                        class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-600 text-xs font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors text-center">
                                                        ยกเลิก
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="text-4xl mb-3">📍</div>
                                            <h4 class="text-base font-bold text-slate-800">ยังมีสถานที่ในระบบ</h4>
                                            <p class="text-sm text-slate-500 font-medium">เพิ่มสถานที่คลังสินค้าจากฟอร์มด้านซ้าย</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    @if($locations->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $locations->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Desktop Table Edit
        function showEditForm(id) {
            document.querySelectorAll(`.display-cell-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.edit-cell-${id}`).forEach(el => el.classList.remove('hidden'));
            document.getElementById(`row-${id}`).classList.remove('hover:bg-indigo-50/30');
            document.getElementById(`row-${id}`).classList.add('bg-slate-50');
        }

        function hideEditForm(id) {
            document.querySelectorAll(`.display-cell-${id}`).forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll(`.edit-cell-${id}`).forEach(el => el.classList.add('hidden'));
            document.getElementById(`row-${id}`).classList.add('hover:bg-indigo-50/30');
            document.getElementById(`row-${id}`).classList.remove('bg-slate-50');
        }

        // Mobile Card Edit
        function showEditFormCard(id) {
            document.querySelectorAll(`.display-card-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.edit-card-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        function hideEditFormCard(id) {
            document.querySelectorAll(`.display-card-${id}`).forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll(`.edit-card-${id}`).forEach(el => el.classList.add('hidden'));
        }

        function filterLocations() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('.location-row');
            let hasVisible = false;

            rows.forEach(row => {
                let searchData = row.getAttribute('data-search') || "";
                if (searchData.includes(input)) {
                    row.style.display = "";
                    hasVisible = true;
                } else {
                    row.style.display = "none";
                }
            });

            // ตรวจสอบจัดการข้อความ "ไม่พบข้อมูล"
            let emptyMsg = document.getElementById('emptySearchMsg');
            if (!hasVisible && input !== "") {
                if (!emptyMsg) {
                    emptyMsg = document.createElement('tr');
                    emptyMsg.id = 'emptySearchMsg';
                    emptyMsg.innerHTML = `<td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-4xl mb-3">🔍</div>
                        <h4 class="text-base font-bold text-slate-800">ไม่พบสถานที่ที่ค้นหา</h4>
                        <p class="text-sm text-slate-500 font-medium">ไม่มีสถานที่ที่ตรงกับ: "${input}"</p>
                    </td>`;
                    const tbody = document.getElementById('locationsTableBody');
                    if (tbody) tbody.appendChild(emptyMsg);
                } else {
                    emptyMsg.style.display = "";
                    emptyMsg.querySelector('p').innerHTML = `ไม่มีสถานที่ที่ตรงกับ: "${input}"`;
                }
            } else if (emptyMsg) {
                emptyMsg.style.display = "none";
            }
        }
    </script>
</x-app-layout>
