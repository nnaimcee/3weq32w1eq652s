<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📥 สินค้าระหว่างทาง (Goods in Transit)</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

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

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-bold">สินค้าที่กำลังจัดส่ง</p>
                            <p class="text-3xl font-black text-yellow-600">{{ $stocksInTransit->count() }} รายการ</p>
                        </div>
                        <span class="text-4xl">🚚</span>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-bold">จำนวนชิ้นทั้งหมดใน Transit</p>
                            <p class="text-3xl font-black text-blue-600">{{ number_format($stocksInTransit->sum('quantity')) }} ชิ้น</p>
                        </div>
                        <span class="text-4xl">📦</span>
                    </div>
                </div>
            </div>

            {{-- Transit Items Table --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-4 bg-gray-50 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-gray-700 text-lg">📋 รายการที่รออยู่ใน Transit</h3>
                        <a href="{{ route('transfer.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold">
                            ← กลับหน้าส่งของ
                        </a>
                    </div>
                </div>

                @if($stocksInTransit->isEmpty())
                    <div class="p-12 text-center">
                        <span class="text-5xl block mb-3">✅</span>
                        <p class="text-gray-400 text-lg font-bold">ไม่มีสินค้าค้างอยู่ใน Transit</p>
                        <p class="text-gray-300 text-sm">สินค้าทั้งหมดถูกรับเข้าเรียบร้อยแล้ว</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">สินค้า</th>
                                    <th class="px-4 py-3 text-center">จำนวน</th>
                                    <th class="px-4 py-3">ต้นทาง</th>
                                    <th class="px-4 py-3">ปลายทาง</th>
                                    <th class="px-4 py-3">ผู้ส่ง</th>
                                    <th class="px-4 py-3">เวลาส่ง</th>
                                    <th class="px-4 py-3 text-center">รับเข้าตำแหน่ง</th>
                                    <th class="px-4 py-3 text-center">ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stocksInTransit as $st)
                                    @php
                                        // หา Transaction ที่ตรงกับ stock นี้
                                        $tx = $pendingTransactions->firstWhere('product_id', $st->product_id);
                                    @endphp
                                    <tr class="hover:bg-yellow-50">
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-800">{{ $st->product->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $st->product->sku ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-bold">{{ $st->quantity }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($tx && $tx->fromLocation)
                                                <span class="text-gray-600">📍 {{ $tx->fromLocation->name }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($tx && $tx->toLocation)
                                                <span class="font-bold text-blue-600">🎯 {{ $tx->toLocation->name }}</span>
                                            @else
                                                <span class="text-gray-400">ยังไม่ระบุ</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($tx && $tx->user)
                                                <span class="text-gray-600">{{ $tx->user->name }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            @if($tx)
                                                {{ $tx->created_at->diffForHumans() }}
                                                <br>
                                                <span class="text-gray-400">{{ $tx->created_at->format('d/m/Y H:i') }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('transfer.receive') }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="stock_id" value="{{ $st->id }}">
                                                <select name="to_location_id" class="text-sm border-gray-300 rounded-lg w-full focus:border-green-500" required>
                                                    @foreach($destinations as $dest)
                                                        <option value="{{ $dest->id }}"
                                                            {{ ($tx && $tx->to_location_id == $dest->id) ? 'selected' : '' }}>
                                                            {{ $dest->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                                <button type="submit"
                                                    onclick="return confirm('⚠️ ยืนยันรับสินค้าเข้าตำแหน่งที่เลือก?')"
                                                    class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded-full transition whitespace-nowrap">
                                                    ✅ ยืนยันรับเข้า
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>