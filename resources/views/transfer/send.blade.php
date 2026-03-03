<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🚚 ย้ายสินค้า</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

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

            <div class="bg-white p-6 shadow-lg sm:rounded-xl border-t-4 border-blue-500">
                <form action="{{ route('transfer.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="location_id" id="location_id">

                    {{-- เลือกสินค้า --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">📦 เลือกสินค้าและตำแหน่งต้นทาง:</label>
                        <select name="product_id" id="product_select" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- เลือกสินค้าที่ต้องการย้าย --</option>
                            @foreach($stocks as $st)
                                <option value="{{ $st->product_id }}" data-location="{{ $st->location_id }}">
                                    {{ $st->product->name }} ({{ $st->location->name }}) — คงเหลือ {{ $st->total_quantity }} ชิ้น
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- เลือกปลายทาง --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">📍 เลือกปลายทาง:</label>
                        <select name="to_location_id" id="to_location_id" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- เลือกตำแหน่งปลายทาง --</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->name }} {{ $dest->zone ? '(Zone: ' . $dest->zone . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- จำนวน --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">🔢 จำนวนที่จะย้าย:</label>
                        <input type="number" name="quantity" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required min="1" placeholder="ระบุจำนวน">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-xl w-full text-lg shadow-lg transition">
                        🚚 ส่งสินค้าไปยังปลายทาง
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('transfer.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold">
                        📋 ดูรายการค้างอยู่ใน Transit (รอรับเข้า) →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('product_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const locationId = selectedOption.getAttribute('data-location');
            document.getElementById('location_id').value = locationId;
        });
    </script>
</x-app-layout>