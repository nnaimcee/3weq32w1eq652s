<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📍 จัดการสถานที่จัดเก็บ
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Form เพิ่มสถานที่ใหม่ --}}
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-lg rounded-2xl p-6 border-t-4 border-indigo-500 lg:sticky lg:top-6">
                        <h3 class="font-bold text-lg text-indigo-700 mb-4">➕ เพิ่มสถานที่ใหม่</h3>
                        <form action="{{ route('locations.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-bold text-gray-600 mb-1">ชื่อสถานที่ <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="เช่น Z1-S1-B01"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-bold text-gray-600 mb-1">โซน (Zone)</label>
                                <input type="text" name="zone" placeholder="เช่น A, B, Cold"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-1">ชั้น (Shelf)</label>
                                    <input type="text" name="shelf" placeholder="เช่น S1"
                                        class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-1">ช่อง (Bin)</label>
                                    <input type="text" name="bin" placeholder="เช่น B01"
                                        class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-bold text-gray-600 mb-1">ประเภท <span class="text-red-500">*</span></label>
                                <select name="type" required
                                    class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="storage">📦 Storage (จัดเก็บ)</option>
                                    <option value="transit">🚚 Transit (พักสินค้า)</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-600 mb-1">ความจุ (Capacity) <span class="text-xs text-gray-400">ชิ้น</span></label>
                                <input type="number" name="capacity" min="1" value="5000" placeholder="5000"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2.5 rounded-xl shadow-lg transition">
                                💾 บันทึกสถานที่ใหม่
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ตารางรายการสถานที่ --}}
                <div class="lg:col-span-2">
                    {{-- Search Bar --}}
                    <div class="mb-4 bg-white p-4 rounded-xl shadow border border-gray-200">
                        <form action="{{ route('locations.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">🔍</span>
                                </div>
                                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="พิมพ์ชื่อสถานที่ หรือ Zone เพื่อค้นหาทันที..." 
                                    onkeyup="filterLocations()"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="hidden sm:block bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded-lg shadow transition w-full sm:w-auto text-sm">
                                    ค้นหา
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('locations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg shadow transition flex items-center justify-center w-full sm:w-auto text-sm">
                                        ล้าง
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b flex items-center justify-between">
                            <h3 class="font-bold text-gray-700">📋 รายการสถานที่ ({{ $locations->total() }} แห่ง)</h3>
                        </div>

                        {{-- Mobile Card View --}}
                        <div class="sm:hidden divide-y divide-gray-100" id="locationsCardList">
                            @forelse ($locations as $location)
                                <div class="location-row p-4" id="card-{{ $location->id }}" data-search="{{ mb_strtolower($location->name . ' ' . $location->zone . ' ' . $location->shelf . ' ' . $location->bin) }}">
                                    {{-- Display Mode --}}
                                    <div class="display-card-{{ $location->id }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <p class="font-mono font-bold text-gray-800">{{ $location->name }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $location->zone ?? '-' }}</span>
                                                    @if($location->type === 'storage')
                                                        <span class="text-green-600 font-bold text-xs">📦 Storage</span>
                                                    @else
                                                        <span class="text-orange-600 font-bold text-xs">🚚 Transit</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                @if($location->status === 'active')
                                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">✅ Active</span>
                                                @elseif($location->status === 'full')
                                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-bold">📦 Full</span>
                                                @else
                                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs font-bold">❌ Inactive</span>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">สินค้า: <span class="font-bold text-blue-600">{{ number_format($location->stocks_sum_quantity ?? 0) }}</span></p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <button onclick="showEditFormCard({{ $location->id }})"
                                                class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold py-2 px-3 rounded-lg transition">
                                                ✏️ แก้ไข
                                            </button>
                                            <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                                onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสถานที่นี้?');" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full bg-red-100 hover:bg-red-200 text-red-600 text-xs font-bold py-2 px-3 rounded-lg transition">
                                                    🗑️ ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    {{-- Edit Mode (hidden by default) --}}
                                    <div class="hidden edit-card-{{ $location->id }}">
                                        <form action="{{ route('locations.update', $location->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="col-span-2">
                                                    <label class="text-xs text-gray-500 font-bold">ชื่อ</label>
                                                    <input type="text" name="name" value="{{ $location->name }}" required class="w-full border-gray-300 rounded text-sm">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-bold">Zone</label>
                                                    <input type="text" name="zone" value="{{ $location->zone }}" class="w-full border-gray-300 rounded text-sm">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-bold">Shelf</label>
                                                    <input type="text" name="shelf" value="{{ $location->shelf }}" class="w-full border-gray-300 rounded text-sm">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-bold">Bin</label>
                                                    <input type="text" name="bin" value="{{ $location->bin }}" class="w-full border-gray-300 rounded text-sm">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-bold">ประเภท</label>
                                                    <select name="type" class="w-full border-gray-300 rounded text-sm">
                                                        <option value="storage" {{ $location->type === 'storage' ? 'selected' : '' }}>Storage</option>
                                                        <option value="transit" {{ $location->type === 'transit' ? 'selected' : '' }}>Transit</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-bold">สถานะ</label>
                                                    <select name="status" class="w-full border-gray-300 rounded text-sm">
                                                        <option value="active" {{ $location->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $location->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="full" {{ $location->status === 'full' ? 'selected' : '' }}>Full</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="flex gap-2 mt-2">
                                                <button type="submit" class="flex-1 bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-2 px-3 rounded transition">💾 บันทึก</button>
                                                <button type="button" onclick="hideEditFormCard({{ $location->id }})" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white text-xs font-bold py-2 px-3 rounded transition">✕ ยกเลิก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400">ยังไม่มีสถานที่ในระบบ — กรุณาเพิ่มสถานที่ใหม่</div>
                            @endforelse
                        </div>

                        {{-- Desktop Table View --}}
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">ชื่อ</th>
                                        <th class="px-4 py-3">Zone</th>
                                        <th class="px-4 py-3">ประเภท</th>
                                        <th class="px-4 py-3">สถานะ</th>
                                        <th class="px-4 py-3 text-center">สินค้า / Capacity</th>
                                        <th class="px-4 py-3 text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="locationsTableBody">
                                    @forelse ($locations as $location)
                                        <tr class="hover:bg-gray-50 location-row" id="row-{{ $location->id }}" data-search="{{ mb_strtolower($location->name . ' ' . $location->zone . ' ' . $location->shelf . ' ' . $location->bin) }}">
                                            {{-- Display Mode --}}
                                            <td class="px-4 py-3 font-mono font-bold display-cell-{{ $location->id }}">
                                                {{ $location->name }}
                                            </td>
                                            <td class="px-4 py-3 display-cell-{{ $location->id }}">
                                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $location->zone ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3 display-cell-{{ $location->id }}">
                                                @if($location->type === 'storage')
                                                    <span class="text-green-600 font-bold">📦 Storage</span>
                                                @else
                                                    <span class="text-orange-600 font-bold">🚚 Transit</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 display-cell-{{ $location->id }}">
                                                @if($location->status === 'active')
                                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">✅ Active</span>
                                                @elseif($location->status === 'full')
                                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-bold">📦 Full</span>
                                                @else
                                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs font-bold">❌ Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center display-cell-{{ $location->id }}">
                                                <span class="font-bold text-blue-600">{{ number_format($location->stocks_sum_quantity ?? 0) }}</span>
                                                <span class="text-gray-400 text-xs">/{{ number_format($location->capacity ?? 5000) }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center display-cell-{{ $location->id }}">
                                                <div class="flex items-center justify-center gap-1">
                                                    <button onclick="showEditForm({{ $location->id }})"
                                                        class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold py-1 px-3 rounded-full transition">
                                                        ✏️ แก้ไข
                                                    </button>
                                                    <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                                        onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสถานที่นี้?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-500 hover:text-red-700 text-xs font-bold py-1 px-2 transition">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                            {{-- Edit Mode (hidden by default) --}}
                                            <td colspan="6" class="hidden edit-cell-{{ $location->id }} px-4 py-3">
                                                <form action="{{ route('locations.update', $location->id) }}" method="POST"
                                                    class="flex flex-wrap items-end gap-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="flex-1 min-w-[120px]">
                                                        <label class="text-xs text-gray-500 font-bold">ชื่อ</label>
                                                        <input type="text" name="name" value="{{ $location->name }}" required
                                                            class="w-full border-gray-300 rounded text-sm">
                                                    </div>
                                                    <div class="w-20">
                                                        <label class="text-xs text-gray-500 font-bold">Zone</label>
                                                        <input type="text" name="zone" value="{{ $location->zone }}"
                                                            class="w-full border-gray-300 rounded text-sm">
                                                    </div>
                                                    <div class="w-16">
                                                        <label class="text-xs text-gray-500 font-bold">Shelf</label>
                                                        <input type="text" name="shelf" value="{{ $location->shelf }}"
                                                            class="w-full border-gray-300 rounded text-sm">
                                                    </div>
                                                    <div class="w-16">
                                                        <label class="text-xs text-gray-500 font-bold">Bin</label>
                                                        <input type="text" name="bin" value="{{ $location->bin }}"
                                                            class="w-full border-gray-300 rounded text-sm">
                                                    </div>
                                                    <div class="w-28">
                                                        <label class="text-xs text-gray-500 font-bold">ประเภท</label>
                                                        <select name="type" class="w-full border-gray-300 rounded text-sm">
                                                            <option value="storage" {{ $location->type === 'storage' ? 'selected' : '' }}>Storage</option>
                                                            <option value="transit" {{ $location->type === 'transit' ? 'selected' : '' }}>Transit</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-28">
                                                        <label class="text-xs text-gray-500 font-bold">สถานะ</label>
                                                        <select name="status" class="w-full border-gray-300 rounded text-sm">
                                                            <option value="active" {{ $location->status === 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $location->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            <option value="full" {{ $location->status === 'full' ? 'selected' : '' }}>Full</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-24">
                                                        <label class="text-xs text-gray-500 font-bold">Capacity</label>
                                                        <input type="number" name="capacity" min="1"
                                                            value="{{ $location->capacity ?? 5000 }}"
                                                            class="w-full border-gray-300 rounded text-sm">
                                                    </div>
                                                    <div class="flex gap-1">
                                                        <button type="submit"
                                                            class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-2 px-3 rounded transition">
                                                            💾 บันทึก
                                                        </button>
                                                        <button type="button" onclick="hideEditForm({{ $location->id }})"
                                                            class="bg-gray-400 hover:bg-gray-600 text-white text-xs font-bold py-2 px-3 rounded transition">
                                                            ✕
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                                ยังไม่มีสถานที่ในระบบ — กรุณาเพิ่มสถานที่ใหม่
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $locations->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Desktop Table Edit
        function showEditForm(id) {
            document.querySelectorAll(`.display-cell-${id}`).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll(`.edit-cell-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        function hideEditForm(id) {
            document.querySelectorAll(`.display-cell-${id}`).forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll(`.edit-cell-${id}`).forEach(el => el.classList.add('hidden'));
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
                    emptyMsg.innerHTML = `<td colspan="6" class="px-4 py-8 text-center text-gray-500 font-bold"> ไม่พบสถานที่ที่ตรงกับ: "${input}" </td>`;
                    const tbody = document.getElementById('locationsTableBody');
                    if (tbody) tbody.appendChild(emptyMsg);
                } else {
                    emptyMsg.innerHTML = `<td colspan="6" class="px-4 py-8 text-center text-gray-500 font-bold"> ไม่พบสถานที่ที่ตรงกับ: "${input}" </td>`;
                    emptyMsg.style.display = "";
                }
            } else if (emptyMsg) {
                emptyMsg.style.display = "none";
            }
        }
    </script>
</x-app-layout>
