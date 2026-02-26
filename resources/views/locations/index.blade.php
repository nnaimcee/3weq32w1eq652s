<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📍 จัดการสถานที่จัดเก็บ (Locations)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

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
                    <div class="bg-white shadow-lg rounded-2xl p-6 border-t-4 border-indigo-500 sticky top-6">
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
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-600 mb-1">ประเภท <span class="text-red-500">*</span></label>
                                <select name="type" required
                                    class="w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="storage">📦 Storage (จัดเก็บ)</option>
                                    <option value="transit">🚚 Transit (พักสินค้า)</option>
                                </select>
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
                    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b flex items-center justify-between">
                            <h3 class="font-bold text-gray-700">📋 รายการสถานที่ทั้งหมด ({{ $locations->count() }} แห่ง)</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">ชื่อ</th>
                                        <th class="px-4 py-3">Zone</th>
                                        <th class="px-4 py-3">ประเภท</th>
                                        <th class="px-4 py-3">สถานะ</th>
                                        <th class="px-4 py-3 text-center">สินค้า</th>
                                        <th class="px-4 py-3 text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($locations as $location)
                                        <tr class="hover:bg-gray-50" id="row-{{ $location->id }}">
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
                </div>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(id) {
            // ซ่อน display cells
            document.querySelectorAll(`.display-cell-${id}`).forEach(el => el.classList.add('hidden'));
            // แสดง edit cell
            document.querySelectorAll(`.edit-cell-${id}`).forEach(el => el.classList.remove('hidden'));
        }

        function hideEditForm(id) {
            // แสดง display cells
            document.querySelectorAll(`.display-cell-${id}`).forEach(el => el.classList.remove('hidden'));
            // ซ่อน edit cell
            document.querySelectorAll(`.edit-cell-${id}`).forEach(el => el.classList.add('hidden'));
        }
    </script>
</x-app-layout>
