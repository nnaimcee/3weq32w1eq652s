<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 w-full relative z-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Bento Top KPIs --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                {{-- Main Stat (e.g., Total Stock) --}}
                <div class="col-span-2 row-span-2 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[2rem] p-6 sm:p-8 shadow-lg shadow-indigo-600/20 text-white relative overflow-hidden group flex flex-col justify-between min-h-[220px]">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-[60px] -z-10 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                    <div>
                        <p class="text-sm font-semibold text-indigo-100 uppercase tracking-widest mb-1 opacity-90">Total Stock</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl sm:text-6xl font-black tracking-tight">{{ number_format($totalStock) }}</span>
                            <span class="text-lg font-medium text-indigo-200">ชิ้น</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <a href="{{ route('inventory.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl transition-colors text-sm font-bold backdrop-blur-md border border-white/10 shadow-sm">
                            จัดการสต็อก <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/5 shadow-inner">
                            <span class="text-2xl">📦</span>
                        </div>
                    </div>
                </div>

                {{-- Other Stats --}}
                <div class="col-span-2 md:col-span-2 lg:col-span-1 bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center hover:border-slate-300 hover:shadow-md transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300 group-hover:bg-blue-500 transition-colors"></span> สินค้าทั้งหมด</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalProducts }}</span>
                        <span class="text-xs font-semibold text-slate-400">รายการ</span>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-2 lg:col-span-1 bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center hover:border-slate-300 hover:shadow-md transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400 group-hover:bg-amber-500 transition-colors"></span> ถูกจอง</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalReserved) }}</span>
                        <span class="text-xs font-semibold text-slate-400">ชิ้น</span>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-2 lg:col-span-2 bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex items-center justify-between hover:border-slate-300 hover:shadow-md transition-all group relative overflow-hidden">
                    <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-emerald-50 to-transparent -z-10 group-hover:w-48 transition-all duration-500"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 group-hover:bg-emerald-500 transition-colors"></span> พร้อมจ่าย</p>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalAvailable) }}</span>
                            <span class="text-xs font-semibold text-slate-400">ชิ้น</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-emerald-100 group-hover:-translate-y-1 transition-transform">✅</div>
                </div>

                <div class="col-span-2 md:col-span-2 lg:col-span-1 bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center hover:border-slate-300 hover:shadow-md transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span> ระหว่างทาง</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-rose-600 tracking-tight">{{ number_format($totalTransit) }}</span>
                        <span class="text-xs font-semibold text-slate-400">ชิ้น</span>
                    </div>
                </div>

                 <div class="col-span-2 md:col-span-2 lg:col-span-1 bg-white rounded-3xl p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 flex flex-col justify-center hover:border-slate-300 hover:shadow-md transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-fuchsia-400 group-hover:bg-fuchsia-500 transition-colors"></span> ตำแหน่ง</p>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalLocations }}</span>
                        <span class="text-xs font-semibold text-slate-400">โซน</span>
                    </div>
                </div>
            </div>

            {{-- Layout Grid for Charts and Feeds --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left/Center Area (Charts & Activities) --}}
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    {{-- Chart --}}
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 p-6 sm:p-8 flex flex-col min-h-[360px]">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                                <span class="bg-indigo-50 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center shadow-sm border border-indigo-100">📈</span> ความเคลื่อนไหว 7 วันล่าสุด
                            </h3>
                        </div>
                        <div class="flex-1 w-full min-h-[260px] relative">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>

                    {{-- Activity Feed Table --}}
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                                <span class="bg-sky-50 text-sky-500 w-8 h-8 rounded-lg flex items-center justify-center shadow-sm border border-sky-100">⚡</span> กิจกรรมล่าสุด
                            </h3>
                            <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:text-indigo-700 bg-indigo-50/50 hover:bg-indigo-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors border border-indigo-100/50">ดูทั้งหมด</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-white text-[10px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-100">
                                        <th class="px-6 py-4 w-12 text-center">ประเภท</th>
                                        <th class="px-6 py-4">ข้อความ/รายการ</th>
                                        <th class="px-6 py-4 text-right">จำนวน</th>
                                        <th class="px-6 py-4 text-right">เวลา</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @forelse($recentActivities as $activity)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $badgeClass = match($activity->type) {
                                                    'IN' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'OUT' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                    'TRANSFER' => 'bg-blue-100 text-blue-700 border-blue-200', 'RESERVE' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    'RELEASE' => 'bg-teal-100 text-teal-700 border-teal-200', default => 'bg-slate-100 text-slate-700 border-slate-200',
                                                };
                                                $icon = match($activity->type) {
                                                    'IN' => '📥', 'OUT' => '📤', 'TRANSFER' => '🚚', 'RESERVE' => '🔒', 'RELEASE' => '🔓', default => '📋',
                                                };
                                            @endphp
                                            <div class="w-8 h-8 mx-auto rounded-lg {{ $badgeClass }} flex items-center justify-center text-sm shadow-sm opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all border">
                                                {{ $icon }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-slate-800">{{ $activity->product->name ?? '-' }} <span class="text-xs font-semibold text-slate-400 bg-white border border-slate-200 px-1.5 py-0.5 rounded shadow-sm ml-1">{{ $activity->product->sku ?? '-' }}</span></p>
                                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-1 font-medium">
                                                @if($activity->fromLocation) <span class="text-slate-400">จาก:</span> <span class="text-slate-700">{{ $activity->fromLocation->name }}</span> @endif
                                                @if($activity->fromLocation && $activity->toLocation) <span class="text-slate-300 mx-1">→</span> @endif
                                                @if($activity->toLocation) <span class="text-slate-400">ถึง:</span> <span class="text-slate-700">{{ $activity->toLocation->name }}</span> @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-black text-slate-700">{{ number_format($activity->quantity) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="text-xs font-bold text-slate-500 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $activity->user->name ?? 'System' }}</p>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center">
                                            <p class="text-slate-400 font-medium">ยังไม่มีกิจกรรมล่าสุด</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Right Area (Actionable Alerts & Zone Chart) --}}
                <div class="lg:col-span-1 flex flex-col gap-6">

                    {{-- Dynamic Action Cards --}}
                    @if($lowStockCount > 0 || $pendingTransfers > 0 || $pendingLocationReservations > 0)
                    <div class="bg-rose-50/50 rounded-[2rem] shadow-inner border border-rose-100/50 p-2 space-y-2">
                        
                        @if($lowStockCount > 0)
                        <div class="bg-white rounded-3xl p-5 shadow-sm border border-red-100 flex flex-col relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-red-500/10 rounded-bl-full pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-red-800 flex items-center gap-2 text-sm"><span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> สินค้าใกล้หมด</h4>
                                <span class="bg-red-100 text-red-700 text-xs font-black px-2 py-1 rounded-md">{{ $lowStockCount }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 font-medium leading-relaxed">มีสินค้าที่มีระดับสต็อกต่ำกว่าเกณฑ์ขั้นต่ำ ควรดำเนินการสั่งซื้อเพิ่มเติม</p>
                            <a href="{{ route('inventory.index') }}" class="text-xs font-bold text-red-600 hover:text-red-700 hover:underline inline-flex items-center w-max">ตรวจสอบสินค้า <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                        </div>
                        @endif

                        @if($pendingTransfers > 0)
                        <div class="bg-white rounded-3xl p-5 shadow-sm border border-orange-100 flex flex-col relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-orange-500/10 rounded-bl-full pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-orange-800 flex items-center gap-2 text-sm"><span class="w-2 h-2 rounded-full bg-orange-400"></span> สินค้าระหว่างทาง</h4>
                                <span class="bg-orange-100 text-orange-700 text-xs font-black px-2 py-1 rounded-md">{{ $pendingTransfers }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 font-medium leading-relaxed">มีสินค้ารอย้ายเข้าสต็อก โปรดรอรับและตรวจนับก่อนกดยืนยัน</p>
                            <a href="{{ route('transfer.pending') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 hover:underline inline-flex items-center w-max">ดูรายการย้าย <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                        </div>
                        @endif

                        @if($pendingLocationReservations > 0)
                         <div class="bg-white rounded-3xl p-5 shadow-sm border border-purple-100 flex flex-col relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-purple-500/10 rounded-bl-full pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-purple-800 flex items-center gap-2 text-sm"><span class="w-2 h-2 rounded-full bg-purple-400"></span> รายการจองพื้นที่</h4>
                                <span class="bg-purple-100 text-purple-700 text-xs font-black px-2 py-1 rounded-md">{{ $pendingLocationReservations }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4 font-medium leading-relaxed">มีการจองพื้นที่เพื่อเตรียมสินค้าเข้าคลัง</p>
                            <a href="{{ route('location-reservations.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 hover:underline inline-flex items-center w-max">จัดการพื้นที่จอง <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Zone Chart --}}
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-200/60 p-6 flex flex-col">
                        <h3 class="font-bold text-slate-800 mb-6 text-base flex items-center gap-2">
                             <span class="bg-fuchsia-50 text-fuchsia-500 w-8 h-8 rounded-lg flex items-center justify-center shadow-sm border border-fuchsia-100">🏭</span> สต็อกแยกตาม Zone
                        </h3>
                        <div class="flex items-center justify-center w-full min-h-[220px]">
                            <canvas id="zoneChart"></canvas>
                        </div>
                    </div>

                    {{-- Quick Action (Bottom subtle) --}}
                    <a href="{{ route('scanner.index') }}" class="bg-slate-800 text-white rounded-3xl p-6 shadow-md hover:bg-slate-900 transition-colors flex items-center justify-between group">
                        <div>
                            <p class="text-sm font-bold opacity-90 mb-1">พร้อมทำงาน?</p>
                            <p class="text-2xl font-black">สแกนรับ/เบิก</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform backdrop-blur-sm shadow-inner text-xl">📷</div>
                    </a>

                </div>

            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        // Style variables for charts to match SaaS clean look
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8'; // slate-400
        Chart.defaults.scale.grid.color = 'rgba(2f, 41, 55, 0.05)'; // subtle grid

        // Bar Chart: Daily Activity
        new Chart(document.getElementById('dailyChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($dailyData)->pluck('label')) !!},
                datasets: [
                    {
                        label: ' รับเข้า',
                        data: {!! json_encode(collect($dailyData)->pluck('in')) !!},
                        backgroundColor: '#10b981', // emerald-500
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: ' เบิกออก',
                        data: {!! json_encode(collect($dailyData)->pluck('out')) !!},
                        backgroundColor: '#f43f5e', // rose-500
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: ' ย้ายของ',
                        data: {!! json_encode(collect($dailyData)->pluck('transfer')) !!},
                        backgroundColor: '#3b82f6', // blue-500
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: ' จองพื้นที่',
                        data: {!! json_encode(collect($dailyData)->pluck('reserve')) !!},
                        backgroundColor: '#f59e0b', // amber-500
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { 
                        position: 'top', 
                        align: 'end',
                        labels: { 
                            usePointStyle: true, 
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 20, 
                            font: { size: 11, weight: 'bold' } 
                        } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 12, family: "'Inter', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 4
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { drawBorder: false },
                        ticks: { precision: 0, padding: 10 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { padding: 5, font: { weight: '600' } }
                    }
                }
            }
        });

        // Donut Chart: Stock by Zone
        const zoneColors = [
            '#6366f1', // indigo-500
            '#14b8a6', // teal-500
            '#f59e0b', // amber-500
            '#ec4899', // pink-500
            '#0ea5e9', // sky-500
            '#8b5cf6', // violet-500
            '#ef4444', // red-500
            '#84cc16'  // lime-500
        ];
        
        new Chart(document.getElementById('zoneChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($stockByZone->keys()) !!},
                datasets: [{
                    data: {!! json_encode($stockByZone->values()) !!},
                    backgroundColor: zoneColors.slice(0, {{ $stockByZone->count() }}),
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            usePointStyle: true, 
                            padding: 15, 
                            font: { size: 11, weight: 'bold' } 
                        } 
                    },
                     tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        bodyFont: { size: 13, weight: 'bold', family: "'Inter', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });
    </script>
</x-app-layout>