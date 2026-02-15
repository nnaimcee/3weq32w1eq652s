<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">✨ เพิ่มสินค้าใหม่ & สร้างบาร์โค้ด</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
                <form action="{{ route('products.store') }}" method="POST" id="productForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold mb-2">ชื่อสินค้า:</label>
                            <input type="text" name="name" id="p_name"
                                class="w-full border-gray-300 rounded-lg" required>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">SKU (รหัสอ้างอิง):</label>
                            <input type="text" name="sku" id="p_sku" value="{{ $nextSku }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <p class="text-xs text-gray-500 mt-1">* ระบบเจนรหัสให้อัตโนมัติ คุณสามารถแก้ไขได้</p>
                        </div>
                    </div>

                    <div
                        class="mt-8 p-6 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center">
                        <h3 class="text-sm font-bold text-gray-500 mb-4 uppercase">ตัวอย่างสติกเกอร์ (Preview)</h3>
                        <div id="printableSticker" class="bg-white p-4 border shadow-sm text-center"
                            style="width: 50mm; height: 30mm;">
                            <div class="text-[10px] font-bold truncate mb-1" id="preview_name">Product Name</div>
                            <div id="barcode_container" class="flex justify-center mb-1">
                                {!! DNS1D::getBarcodeHTML('12345678', 'C128', 1.5, 33) !!}
                            </div>
                            <div class="text-[10px] font-mono" id="preview_sku">{{ $nextSku }}</div>
                        </div>

                        <button type="button" onclick="printSticker()"
                            class="mt-4 bg-gray-800 text-white px-6 py-2 rounded-full hover:bg-black flex items-center gap-2">
                            🖨️ พิมพ์สติกเกอร์
                        </button>
                    </div>

                    <div class="mt-8">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 shadow-lg transition">
                            💾 บันทึกสินค้าเข้าสู่ระบบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update Preview Real-time
        document.getElementById('p_name').addEventListener('input', e => document.getElementById('preview_name').innerText =
            e.target.value);
        document.getElementById('p_sku').addEventListener('input', e => document.getElementById('preview_sku').innerText = e
            .target.value);

        function printSticker() {
            const printContent = document.getElementById('printableSticker').outerHTML;
            const originalContent = document.body.innerHTML;

            // ปรับแต่ง CSS สำหรับการพิมพ์สติกเกอร์โดยเฉพาะ
            document.body.innerHTML = `
                <style>
                    @page { size: 50mm 30mm; margin: 0; }
                    body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
                </style>
                ${printContent}
            `;

            window.print();
            location.reload(); // รีโหลดหน้าเพื่อกลับมาสถานะปกติ
        }
    </script>
</x-app-layout>
