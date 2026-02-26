<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 คลังสินค้าภาพรวม (WMS Dashboard)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Top Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-indigo-500">
                    <p class="text-xs text-gray-500 font-bold">📋 สินค้า</p>
                    <p class="text-2xl font-black text-indigo-600">{{ $totalProducts }}</p>
                    <p class="text-xs text-gray-400">รายการ</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 font-bold">📦 สต็อกในคลัง</p>
                    <p class="text-2xl font-black text-blue-600">{{ number_format($totalStock) }}</p>
                    <p class="text-xs text-gray-400">ชิ้น</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                    <p class="text-xs text-gray-500 font-bold">🔒 ถูกจอง</p>
                    <p class="text-2xl font-black text-yellow-600">{{ number_format($totalReserved) }}</p>
                    <p class="text-xs text-gray-400">ชิ้น</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 font-bold">✅ พร้อมจ่าย</p>
                    <p class="text-2xl font-black text-green-600">{{ number_format($totalAvailable) }}</p>
                    <p class="text-xs text-gray-400">ชิ้น</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-500">
                    <p class="text-xs text-gray-500 font-bold">🚚 ระหว่างทาง</p>
                    <p class="text-2xl font-black text-orange-500">{{ number_format($totalTransit) }}</p>
                    <p class="text-xs text-gray-400">ชิ้น</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
                    <p class="text-xs text-gray-500 font-bold">📍 ตำแหน่ง</p>
                    <p class="text-2xl font-black text-purple-600">{{ $totalLocations }}</p>
                    <p class="text-xs text-gray-400">ตำแหน่ง</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 border-l-4 {{ $lowStockCount > 0 ? 'border-red-500' : 'border-gray-300' }}">
                    <p class="text-xs text-gray-500 font-bold">⚠️ Low Stock</p>
                    <p class="text-2xl font-black {{ $lowStockCount > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $lowStockCount }}</p>
                    <p class="text-xs text-gray-400">รายการ</p>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="font-bold text-gray-700 mb-4">📈 ความเคลื่อนไหว 7 วันล่าสุด</h3>
                    <div style="position: relative; height: 280px;">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="font-bold text-gray-700 mb-4">🏭 สต็อกแยกตาม Zone</h3>
                    <div class="flex items-center justify-center" style="height: 220px;">
                        <canvas id="zoneChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column: Low Stock + Pending Transfers --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Low Stock Alert --}}
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden {{ $lowStockCount > 0 ? 'border-2 border-red-200' : '' }}">
                        <div class="p-4 border-b {{ $lowStockCount > 0 ? 'bg-red-50' : 'bg-gray-50' }}">
                            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                                ⚠️ สินค้าใกล้หมด (Low Stock)
                                @if($lowStockCount > 0)
                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">{{ $lowStockCount }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="p-4">
                            @if($lowStockCount > 0)
                                <div class="space-y-3">
                                    @foreach($lowStockProducts as $lp)
                                    @php
                                        $currentStock = $lp->stocks_sum_quantity ?? 0;
                                        $minStock = 50; // กำหนดขั้นต่ำเป็น 50 ตาม Backend
                                        $percentage = round(($currentStock / $minStock) * 100);
                                        $barColor = $currentStock <= 0 ? 'bg-red-600' : ($percentage <= 25 ? 'bg-red-500' : ($percentage <= 50 ? 'bg-orange-500' : 'bg-yellow-500'));
                                        $isOutOfStock = $currentStock <= 0;
                                    @endphp
                                    <div class="{{ $isOutOfStock ? 'bg-red-100 border-red-300' : 'bg-red-50 border-red-100' }} rounded-xl p-3 border">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-bold text-gray-800 text-sm truncate">{{ $lp->name }}</span>
                                            @if($isOutOfStock)
                                                <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full font-bold animate-pulse">หมดสต็อก!</span>
                                            @else
                                                <span class="text-xs text-gray-400 font-mono">{{ $lp->sku }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 text-xs mb-2">
                                            <span class="text-red-600 font-bold">คงเหลือ: {{ number_format($currentStock) }}</span>
                                            @if($lp->min_stock > 0)
                                                <span class="text-gray-400">/</span>
                                                <span class="text-gray-500">ขั้นต่ำ: {{ number_format($lp->min_stock) }}</span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <span class="text-4xl block mb-2">✅</span>
                                    <p class="text-gray-400 font-bold">สินค้าทุกรายการมีจำนวนเพียงพอ</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($pendingTransfers > 0)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border-2 border-orange-200">
                        <div class="p-4 bg-orange-50 border-b">
                            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                                🚚 สินค้าระหว่างทาง
                                <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingTransfers }}</span>
                            </h3>
                        </div>
                        <div class="p-4 text-center">
                            <p class="text-orange-600 font-bold text-lg">{{ $pendingTransfers }} รายการรอรับ</p>
                            <a href="{{ route('transfer.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold mt-2 inline-block">ดูรายละเอียด →</a>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b">
                            <h3 class="font-bold text-gray-700">⚡ ทางลัด</h3>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('scanner.index') }}" class="flex items-center gap-2 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition text-sm font-bold text-blue-700">📷 สแกน</a>
                            <a href="{{ route('products.create') }}" class="flex items-center gap-2 p-3 rounded-xl bg-green-50 hover:bg-green-100 transition text-sm font-bold text-green-700">➕ เพิ่มสินค้า</a>
                            <a href="{{ route('inventory.index') }}" class="flex items-center gap-2 p-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 transition text-sm font-bold text-indigo-700">📦 สต็อก</a>
                            <a href="{{ route('inventory.map') }}" class="flex items-center gap-2 p-3 rounded-xl bg-purple-50 hover:bg-purple-100 transition text-sm font-bold text-purple-700">🗺️ แผนผัง</a>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Recent Activities --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b flex items-center justify-between">
                            <h3 class="font-bold text-gray-700 text-lg">🔔 กิจกรรมล่าสุด</h3>
                            <a href="{{ route('transactions.index') }}" class="text-blue-500 hover:text-blue-700 text-sm font-bold">ดูทั้งหมด →</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($recentActivities as $activity)
                            <div class="p-4 hover:bg-gray-50 transition flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    @switch($activity->type)
                                        @case('IN')     <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-xl">📥</span> @break
                                        @case('OUT')    <span class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-xl">📤</span> @break
                                        @case('TRANSFER') <span class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-xl">🚚</span> @break
                                        @case('RESERVE') <span class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-xl">🔒</span> @break
                                        @case('RELEASE') <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-xl">🔓</span> @break
                                        @default <span class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xl">📋</span>
                                    @endswitch
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        @php
                                            $typeBadge = match($activity->type) {
                                                'IN' => 'bg-green-100 text-green-700', 'OUT' => 'bg-red-100 text-red-700',
                                                'TRANSFER' => 'bg-blue-100 text-blue-700', 'RESERVE' => 'bg-yellow-100 text-yellow-700',
                                                'RELEASE' => 'bg-green-100 text-green-600', default => 'bg-gray-100 text-gray-700',
                                            };
                                            $typeLabel = match($activity->type) {
                                                'IN' => 'รับเข้า', 'OUT' => 'เบิกออก', 'TRANSFER' => 'ย้าย',
                                                'RESERVE' => 'จอง', 'RELEASE' => 'ปลดจอง', default => $activity->type,
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $typeBadge }}">{{ $typeLabel }}</span>
                                        <span class="font-bold text-gray-800 truncate">{{ $activity->product->name ?? '-' }}</span>
                                    </div>
                                    <div class="text-xs text-gray-400 flex items-center gap-2">
                                        <span>{{ $activity->quantity }} ชิ้น</span>
                                        @if($activity->fromLocation) <span>จาก {{ $activity->fromLocation->name }}</span> @endif
                                        @if($activity->toLocation) <span>→ {{ $activity->toLocation->name }}</span> @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                    <p class="text-xs text-gray-400">{{ $activity->user->name ?? 'System' }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-400">
                                <span class="text-4xl block mb-2">📭</span>
                                <p class="font-bold">ยังไม่มีกิจกรรม</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        // Bar Chart: Daily Activity
        new Chart(document.getElementById('dailyChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($dailyData)->pluck('label')) !!},
                datasets: [
                    {
                        label: '📥 รับเข้า',
                        data: {!! json_encode(collect($dailyData)->pluck('in')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: '📤 เบิกออก',
                        data: {!! json_encode(collect($dailyData)->pluck('out')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: '🚚 ย้ายของ',
                        data: {!! json_encode(collect($dailyData)->pluck('transfer')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: '🔒 จอง',
                        data: {!! json_encode(collect($dailyData)->pluck('reserve')) !!},
                        backgroundColor: 'rgba(245, 158, 11, 0.7)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 12 } } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'จำนวนรายการ', font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { precision: 0 }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Donut Chart: Stock by Zone
        const zoneColors = [
            'rgba(99, 102, 241, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(16, 185, 129, 0.8)',
            'rgba(239, 68, 68, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(14, 165, 233, 0.8)',
            'rgba(236, 72, 153, 0.8)', 'rgba(234, 179, 8, 0.8)'
        ];
        new Chart(document.getElementById('zoneChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($stockByZone->keys()) !!},
                datasets: [{
                    data: {!! json_encode($stockByZone->values()) !!},
                    backgroundColor: zoneColors.slice(0, {{ $stockByZone->count() }}),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 12 } } }
                }
            }
        });
    </script>
</x-app-layout>