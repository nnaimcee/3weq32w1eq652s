<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-600 leading-tight">
            {{ __('📤 เบิกสินค้าออก (Outbound)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-red-500">
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        {{ session('error') }}
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

                <form action="{{ route('outbound.store') }}" method="POST" id="outboundForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">สแกนบาร์โค้ดสินค้าที่ต้องการเบิก:</label>
                        <input type="text" name="barcode" id="barcode" required autofocus
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500 border-2" 
                               placeholder="สแกนบาร์โค้ด...">
                    </div>

                    <div id="product_info_box" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-xl transition-all">
                        <h3 class="font-bold text-red-800 mb-2 flex items-center gap-2">
                            <span id="loading_spinner" class="hidden animate-spin">⌛</span>
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">ชื่อสินค้า</p>
                                <p id="display_name" class="font-bold text-lg text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-gray-500">สต็อกคงเหลือรวม</p>
                                <p id="display_stock" class="font-bold text-2xl text-red-600">0</p>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" id="product_id">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวนที่ต้องการเบิก:</label>
                        <input type="number" name="quantity" id="quantity" required min="1"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               placeholder="ระบุจำนวน">
                        <p id="stock_warning" class="text-red-500 text-xs mt-1 hidden font-bold">⚠️ จำนวนที่ระบุมากกว่าสินค้าที่มีในคลัง!</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" id="submit_btn" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full text-lg shadow-lg">
                            📤 ยืนยันการเบิกสินค้า
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
        const stockWarning = document.getElementById('stock_warning');
        const submitBtn = document.getElementById('submit_btn');
        let currentMaxStock = 0;

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
            document.getElementById('loading_spinner').classList.remove('hidden');

            fetch(`/api/products/${barcode}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading_spinner').classList.add('hidden');

                    if (data.success) {
                        // 1. แสดงข้อมูลสินค้าและสต็อกคงเหลือ
                        document.getElementById('display_name').innerText = data.product.name;
                        document.getElementById('display_stock').innerText = data.product.total_stock;
                        document.getElementById('product_id').value = data.product.id;
                        currentMaxStock = data.product.total_stock;
                        
                        // 2. เปิดกล่องโชว์ข้อมูล
                        infoBox.classList.remove('hidden');
                        
                        // 3. กระโดดไปช่องจำนวนอัตโนมัติ
                        quantityInput.focus();
                        validateStock(); // เช็คสต็อกทันทีเผื่อมีค่าค้างอยู่
                    } else {
                        alert('❌ ไม่พบข้อมูลสินค้า: ' + barcode);
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

        // ฟังก์ชันเช็คสต็อกแบบ Real-time
        function validateStock() {
            const val = parseInt(quantityInput.value);
            if (val > currentMaxStock) {
                stockWarning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                stockWarning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        quantityInput.addEventListener('input', validateStock);
    </script>
</x-app-layout>