<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📜 ประวัติความเคลื่อนไหวสินค้า (Transaction History)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100" align="center">
                            <th class="p-3 border">วันที่/เวลา</th>
                            <th class="p-3 border">ประเภท</th>
                            <th class="p-3 border">สินค้า</th>
                            <th class="p-3 border">จำนวน</th>
                            <th class="p-3 border">จากไหน</th>
                            <th class="p-3 border">ไปที่ไหน</th>
                            <th class="p-3 border">ผู้ทำรายการ</th>
                        </tr>
                    </thead>
                    <tbody align="center">
                        @foreach($transactions as $trx)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-3 border">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 border">
                                @if($trx->type == 'IN')
                                    <span class="text-green-600 font-bold">● รับเข้า (IN)</span>
                                @elseif($trx->type == 'OUT')
                                    <span class="text-red-600 font-bold">● เบิกออก (OUT)</span>
                                @else
                                    <span class="text-blue-600 font-bold">● ย้ายของ (TRANSFER)</span>
                                @endif
                            </td>
                            <td class="p-3 border">{{ $trx->product->name }}</td>
                            <td class="p-3 border font-bold">{{ number_format($trx->quantity) }}</td>
                            <td class="p-3 border">{{ $trx->fromLocation->name ?? '-' }}</td>
                            <td class="p-3 border">{{ $trx->toLocation->name ?? '-' }}</td>
                            <td class="p-3 border">{{ $trx->user->name ?? 'System' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>