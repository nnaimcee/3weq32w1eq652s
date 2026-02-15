<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 คลังสินค้าภาพรวม (WMS Dashboard)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">สินค้าทั้งหมด (SKUs)</p>
                    <p class="text-3xl font-bold">{{ $totalProducts }} รายการ</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">จำนวนสต็อกรวม</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($totalStock) }} ชิ้น</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">สินค้าที่ต้องเติม (Low Stock)</p>
                    <p class="text-3xl font-bold text-red-600">{{ $lowStockCount }} รายการ</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4">🔔 กิจกรรมล่าสุด (Recent Activities)</h3>
                    <table class="w-full text-center">
                        <thead>
                            <tr class="text-gray-400 text-sm border-b">
                                <th class="pb-3">เวลา</th>
                                <th class="pb-3">กิจกรรม</th>
                                <th class="pb-3">สินค้า</th>
                                <th class="pb-3">จำนวน</th>
                                <th class="pb-3">ผู้ทำรายการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($recentActivities as $activity)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 text-gray-500">{{ $activity->created_at->diffForHumans() }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $activity->type == 'IN' ? 'bg-green-100 text-green-700' : ($activity->type == 'OUT' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $activity->type }}
                                    </span>
                                </td>
                                <td class="py-3 font-semibold">{{ $activity->product->name }}</td>
                                <td class="py-3">{{ number_format($activity->quantity) }}</td>
                                <td class="py-3 text-gray-600">{{ $activity->user->name ?? 'System' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 text-right">
                        <a href="{{ route('transactions.index') }}" class="text-blue-500 hover:underline text-sm">ดูประวัติทั้งหมด &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>