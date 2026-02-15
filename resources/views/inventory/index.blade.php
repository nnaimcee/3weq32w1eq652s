<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('รายการสินค้าคงคลัง (Inventory List)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-200" align="center">
                                <th class="p-3 border">Barcode</th>
                                <th class="p-3 border">ชื่อสินค้า</th>
                                <th class="p-3 border">สต็อกทั้งหมด</th>
                                <th class="p-3 border">ถูกจอง (Reserve)</th>
                                <th class="p-3 border">คงเหลือพร้อมจ่าย</th>
                                <th class="p-3 border">จัดการ</th>
                                <th class="p-3 border">ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="hover:bg-gray-50" align="center">
                                    <td class="p-3 border">{{ $product->barcode }}</td>
                                    <td class="p-3 border">{{ $product->name }}</td>
                                    <td class="p-3 border text-blue-600 font-bold">
                                        {{ $product->stocks_sum_quantity ?? 0 }}</td>
                                    <td class="p-3 border text-red-500">{{ $product->stocks_sum_reserved_qty ?? 0 }}
                                    </td>
                                    <td class="p-3 border text-green-600 font-bold">
                                        {{ ($product->stocks_sum_quantity ?? 0) - ($product->stocks_sum_reserved_qty ?? 0) }}
                                    </td>
                                    <td class="p-3 border">
                                        <a href="{{ route('products.barcode', $product->id) }}"
                                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-1 px-3 rounded text-sm">
                                            🖨️ พิมพ์บาร์โค้ด
                                        </a>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('⚠️ คุณแน่ใจใช่ไหมที่จะลบสินค้านี้ออกจากสต็อก?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition font-bold">
                                                ลบรายการ
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
