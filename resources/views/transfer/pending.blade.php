<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📥 ยืนยันรับของเข้า (Transfer In)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @if($stocksInTransit->isEmpty())
                    <p class="text-center text-gray-500">ไม่มีสินค้าค้างอยู่ในพื้นที่ Transit</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2">สินค้า</th>
                                <th class="p-2">จำนวน</th>
                                <th class="p-2">ย้ายไปที่...</th>
                                <th class="p-2">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocksInTransit as $st)
                            <form action="{{ route('transfer.receive') }}" method="POST">
                                @csrf
                                <input type="hidden" name="stock_id" value="{{ $st->id }}">
                                <tr align="center">
                                    <td class="p-2">{{ $st->product->name }}</td>
                                    <td class="p-2">{{ $st->quantity }}</td>
                                    <td class="p-2">
                                        <select name="to_location_id" class="text-sm border-gray-300 rounded" required>
                                            @foreach($destinations as $dest)
                                                <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" class="bg-green-600 text-black px-3 py-1 rounded text-sm">ยืนยันรับเข้า</button>
                                    </td>
                                </tr>
                            </form>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>