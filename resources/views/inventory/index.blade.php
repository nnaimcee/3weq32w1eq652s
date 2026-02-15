<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📊 รายการสินค้าคงคลัง (Inventory List)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap min-w-[900px]">
                            <thead>
                                <tr class="bg-gray-200" align="center">
                                    <th class="p-3 border">Barcode</th>
                                    <th class="p-3 border">ชื่อสินค้า</th>
                                    <th class="p-3 border">สต็อกทั้งหมด</th>
                                    <th class="p-3 border">ถูกจอง (Reserve)</th>
                                    <th class="p-3 border text-green-600">คงเหลือพร้อมจ่าย</th>
                                    <th class="p-3 border">พิมพ์สติกเกอร์</th>
                                    <th class="p-3 border">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-50" align="center">
                                        <td class="p-3 border font-mono text-sm">{{ $product->barcode }}</td>
                                        <td class="p-3 border">{{ $product->name }}</td>
                                        <td class="p-3 border text-blue-600 font-bold">
                                            {{ number_format($product->stocks_sum_quantity ?? 0) }}
                                        </td>
                                        <td class="p-3 border text-red-500">
                                            {{ number_format($product->stocks_sum_reserved_qty ?? 0) }}
                                        </td>
                                        <td class="p-3 border text-green-600 font-bold">
                                            {{ number_format(($product->stocks_sum_quantity ?? 0) - ($product->stocks_sum_reserved_qty ?? 0)) }}
                                        </td>
                                        <td class="p-3 border">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                @if($product->barcode_image)
                                                    <img id="barcode-img-{{ $product->id }}" 
                                                         src="{{ asset('storage/barcodes/' . $product->barcode_image) }}" 
                                                         style="height: 33px; width: auto;" 
                                                         alt="barcode">
                                                @else
                                                    <span class="text-xs text-red-500 font-bold bg-red-100 px-2 py-1 rounded">ไม่มีรูปภาพ</span>
                                                @endif

                                                <button type="button" 
                                                    onclick="printStickerDirect('{{ addslashes($product->name) }}', '{{ $product->sku }}', '{{ $product->id }}')"
                                                    class="bg-gray-800 hover:bg-black text-white py-1 px-4 rounded-full text-xs shadow-sm transition">
                                                    🖨️ พิมพ์
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-3 border text-center">
                                            <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                                                onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสินค้านี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition font-bold">
                                                    ลบรายการ
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> </div>
            </div>
        </div>
    </div>

    <script>
        function printStickerDirect(name, sku, productId) {
            // 1. ดึง Source (URL) ของรูปภาพจากหน้าเว็บ
            const imgElement = document.getElementById(`barcode-img-${productId}`);
            
            if (!imgElement) {
                alert("❌ ไม่พบรูปภาพบาร์โค้ดสำหรับสินค้านี้ (อาจต้องบันทึกสินค้าใหม่)");
                return;
            }

            const imgSrc = imgElement.src;

            // 2. สร้างโครงสร้างสติกเกอร์ (ใช้แท็ก <img> เรียกรูปมาแสดง)
            const printContent = `
                <div id="printableSticker" style="width: 50mm; height: 30mm; background: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; box-sizing: border-box; padding: 5px;">
                    <div style="font-size: 14px; font-weight: 600; font-family: sans-serif; margin-bottom: 2px; white-space: nowrap; overflow: hidden; width: 100%; color: black;">${name}</div>
                    
                    <img src="${imgSrc}" style="height: 33px; width: auto; margin-bottom: 2px;">
                    
                    <div style="font-size: 12px; font-family: monospace; font-weight: 500; color: black;">${sku}</div>
                </div>
            `;

            // 3. แทนที่หน้าเว็บเพื่อเตรียมพิมพ์ + เคลียร์ CSS ขยะ
            document.body.innerHTML = `
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-print-color-adjust: exact; }
                    @page { size: 50mm 30mm; margin: 0; }
                    body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: white; }
                </style>
                ${printContent}
            `;

            // 4. สั่งพิมพ์แล้วรีโหลด (ใส่ setTimeout กันรูปบาร์โค้ดโหลดไม่ทันตอนปริ้น)
            setTimeout(() => {
                window.print();
                window.location.reload();
            }, 200); 
        }
    </script>
</x-app-layout>