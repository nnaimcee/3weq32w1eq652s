<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🚚 ย้ายสินค้า (Transfer Out)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form action="{{ route('transfer.send') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="location_id" id="location_id">

                    <div class="mb-4">
                        <label class="block font-bold">เลือกสินค้าและตำแหน่งที่ต้องการย้าย:</label>
                        <select name="product_id" id="product_select" class="w-full border-gray-300 rounded mb-4" required>
                            <option value="">-- เลือกสินค้าที่ต้องการย้าย --</option>
                            @foreach($stocks as $st)
                                <option value="{{ $st->product_id }}" data-location="{{ $st->location_id }}">
                                    {{ $st->product->name }} (อยู่ที่: {{ $st->location->name }}) - คงเหลือทั้งหมด {{ $st->total_quantity }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">จำนวนที่จะย้าย:</label>
                        <input type="number" name="quantity" class="w-full border-gray-300 rounded" required min="1">
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                        ส่งไปยังพื้นที่ Transit
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('product_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const locationId = selectedOption.getAttribute('data-location');
            
            // นำค่า location_id ไปใส่ใน Hidden Input ก่อนกด Submit
            document.getElementById('location_id').value = locationId;
        });
    </script>
</x-app-layout>