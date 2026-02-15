<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📦 รับสินค้าเข้าคลัง (Inbound)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inbound.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">สแกนบาร์โค้ดสินค้า:</label>
                        <input type="text" name="barcode" id="barcode" required autofocus
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 border-2" 
                               placeholder="เอาเมาส์คลิกที่นี่แล้วยิงสแกนเนอร์...">
                    </div>

                    <div id="product_info_box" class="hidden mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl transition-all">
                        <h3 class="font-bold text-blue-800 mb-2 flex items-center gap-2">
                            <span id="loading_spinner" class="hidden animate-spin">⌛</span>
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">ชื่อสินค้า</p>
                                <p id="display_name" class="font-bold text-lg text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-gray-500">รหัส SKU</p>
                                <p id="display_sku" class="font-bold text-lg text-gray-800">-</p>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" id="product_id">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวน (Quantity):</label>
                        <input type="number" name="quantity" id="quantity" required min="1"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               placeholder="ระบุจำนวน">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">เลือกตำแหน่งจัดเก็บ (Location):</label>
                        <select name="location_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- กรุณาเลือก --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full text-lg shadow-lg">
                            💾 ยืนยันการรับเข้า
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        const barcodeInput = document.getElementById('barcode');
        const quantityInput = document.getElementById('quantity');
        const infoBox = document.getElementById('product_info_box');

        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = this.value;

                if (barcode) {
                    fetchProductData(barcode);
                }
            }
        });

        function fetchProductData(barcode) {
            // แสดงสถานะกำลังโหลด
            document.getElementById('loading_spinner').classList.remove('hidden');

            fetch(`/api/products/${barcode}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading_spinner').classList.add('hidden');

                    if (data.success) {
                        // 1. แสดงข้อมูลสินค้า
                        document.getElementById('display_name').innerText = data.product.name;
                        document.getElementById('display_sku').innerText = data.product.sku;
                        document.getElementById('product_id').value = data.product.id;
                        
                        // 2. เปิดกล่องโชว์ข้อมูล
                        infoBox.classList.remove('hidden');
                        
                        // 3. กระโดดไปช่องจำนวนอัตโนมัติ
                        quantityInput.focus();
                    } else {
                        alert('❌ ไม่พบข้อมูลสินค้าสำหรับบาร์โค้ดนี้: ' + barcode);
                        infoBox.classList.add('hidden');
                        barcodeInput.value = '';
                        barcodeInput.focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading_spinner').classList.add('hidden');
                });
        }
    </script>
</x-app-layout>