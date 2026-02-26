<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📜 ประวัติความเคลื่อนไหว (Transaction History)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary --}}
            @php
                $allTrx = $transactions;
                $countIn = \App\Models\Transaction::where('type','IN')->count();
                $countOut = \App\Models\Transaction::where('type','OUT')->count();
                $countTransfer = \App\Models\Transaction::where('type','TRANSFER')->count();
                $countReserve = \App\Models\Transaction::where('type','RESERVE')->count();
                $countRelease = \App\Models\Transaction::where('type','RELEASE')->count();
                $countPending = \App\Models\Transaction::where('status','pending')->count();
            @endphp
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">
                <div class="bg-white rounded-xl shadow p-3 border-l-4 border-green-500 text-center">
                    <p class="text-xs text-gray-400 font-bold">📥 รับเข้า</p>
                    <p class="text-xl font-black text-green-600">{{ number_format($countIn) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-3 border-l-4 border-red-500 text-center">
                    <p class="text-xs text-gray-400 font-bold">📤 เบิกออก</p>
                    <p class="text-xl font-black text-red-600">{{ number_format($countOut) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-3 border-l-4 border-blue-500 text-center">
                    <p class="text-xs text-gray-400 font-bold">🚚 ย้ายของ</p>
                    <p class="text-xl font-black text-blue-600">{{ number_format($countTransfer) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-3 border-l-4 border-yellow-500 text-center">
                    <p class="text-xs text-gray-400 font-bold">🔒 จอง</p>
                    <p class="text-xl font-black text-yellow-600">{{ number_format($countReserve) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-3 border-l-4 border-indigo-500 text-center">
                    <p class="text-xs text-gray-400 font-bold">🔓 ปลดจอง</p>
                    <p class="text-xl font-black text-indigo-600">{{ number_format($countRelease) }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-3 border-l-4 {{ $countPending > 0 ? 'border-orange-500' : 'border-gray-300' }} text-center">
                    <p class="text-xs text-gray-400 font-bold">⏳ รอดำเนินการ</p>
                    <p class="text-xl font-black {{ $countPending > 0 ? 'text-orange-500' : 'text-gray-400' }}">{{ number_format($countPending) }}</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-xl shadow p-4 mb-6 flex flex-wrap items-center gap-3">
                <span class="text-sm font-bold text-gray-500">กรองประเภท:</span>
                <a href="{{ route('transactions.index') }}" class="px-3 py-1 rounded-full text-xs font-bold {{ !request('type') ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">ทั้งหมด</a>
                <a href="{{ route('transactions.index', ['type' => 'IN']) }}" class="px-3 py-1 rounded-full text-xs font-bold {{ request('type') == 'IN' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 hover:bg-green-100' }} transition">📥 รับเข้า</a>
                <a href="{{ route('transactions.index', ['type' => 'OUT']) }}" class="px-3 py-1 rounded-full text-xs font-bold {{ request('type') == 'OUT' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }} transition">📤 เบิกออก</a>
                <a href="{{ route('transactions.index', ['type' => 'TRANSFER']) }}" class="px-3 py-1 rounded-full text-xs font-bold {{ request('type') == 'TRANSFER' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }} transition">🚚 ย้าย</a>
                <a href="{{ route('transactions.index', ['type' => 'RESERVE']) }}" class="px-3 py-1 rounded-full text-xs font-bold {{ request('type') == 'RESERVE' ? 'bg-yellow-600 text-white' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' }} transition">🔒 จอง</a>
                <a href="{{ route('transactions.index', ['type' => 'RELEASE']) }}" class="px-3 py-1 rounded-full text-xs font-bold {{ request('type') == 'RELEASE' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }} transition">🔓 ปลดจอง</a>
            </div>

            {{-- Transaction Cards --}}
            <div class="space-y-3">
                @forelse($transactions as $trx)
                @php
                    $typeConfig = match($trx->type) {
                        'IN' => ['icon' => '📥', 'label' => 'รับเข้า', 'badge' => 'bg-green-100 text-green-700', 'border' => 'border-l-green-500'],
                        'OUT' => ['icon' => '📤', 'label' => 'เบิกออก', 'badge' => 'bg-red-100 text-red-700', 'border' => 'border-l-red-500'],
                        'TRANSFER' => ['icon' => '🚚', 'label' => 'ย้ายของ', 'badge' => 'bg-blue-100 text-blue-700', 'border' => 'border-l-blue-500'],
                        'RESERVE' => ['icon' => '🔒', 'label' => 'จองสินค้า', 'badge' => 'bg-yellow-100 text-yellow-700', 'border' => 'border-l-yellow-500'],
                        'RELEASE' => ['icon' => '🔓', 'label' => 'ปลดจอง', 'badge' => 'bg-indigo-100 text-indigo-700', 'border' => 'border-l-indigo-500'],
                        default => ['icon' => '📋', 'label' => $trx->type, 'badge' => 'bg-gray-100 text-gray-700', 'border' => 'border-l-gray-400'],
                    };
                @endphp
                <div class="bg-white rounded-xl shadow-sm border-l-4 {{ $typeConfig['border'] }} p-4 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="flex-shrink-0 text-2xl mt-1">{{ $typeConfig['icon'] }}</div>

                        {{-- Main Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $typeConfig['badge'] }}">{{ $typeConfig['label'] }}</span>
                                <span class="font-bold text-gray-800">{{ $trx->product->name ?? '-' }}</span>
                                <span class="text-xs text-gray-400 font-mono">({{ $trx->product->sku ?? '-' }})</span>
                                @if($trx->status === 'pending')
                                    <span class="bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full text-xs font-bold animate-pulse">⏳ รอดำเนินการ</span>
                                @elseif($trx->status === 'completed')
                                    <span class="bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full text-[10px] font-bold">✓</span>
                                @endif
                            </div>

                            {{-- Details Row --}}
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500">
                                <span class="font-bold text-gray-700">{{ number_format($trx->quantity) }} ชิ้น</span>

                                @if($trx->fromLocation)
                                    <span>จาก: <span class="font-medium text-gray-700">{{ $trx->fromLocation->name }}</span>
                                        <span class="text-xs text-gray-400">({{ $trx->fromLocation->zone }})</span>
                                    </span>
                                @endif

                                @if($trx->toLocation)
                                    <span>→ ไปที่: <span class="font-medium text-gray-700">{{ $trx->toLocation->name }}</span>
                                        <span class="text-xs text-gray-400">({{ $trx->toLocation->zone }})</span>
                                    </span>
                                @endif

                                @if($trx->ref_doc_no)
                                    <span>📄 เลขอ้างอิง: <span class="font-mono font-medium text-gray-700">{{ $trx->ref_doc_no }}</span></span>
                                @endif
                            </div>

                            {{-- Notes --}}
                            @if($trx->notes)
                            <div class="mt-1 text-xs text-gray-400 italic">
                                💬 {{ $trx->notes }}
                            </div>
                            @endif
                        </div>

                        {{-- Right: Time & User --}}
                        <div class="flex-shrink-0 text-right">
                            <p class="text-sm font-medium text-gray-700">{{ $trx->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $trx->created_at->format('H:i:s') }}</p>
                            <p class="text-xs text-gray-500 mt-1">👤 {{ $trx->user->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow p-12 text-center">
                    <span class="text-5xl block mb-3">📭</span>
                    <p class="text-gray-400 font-bold text-lg">ยังไม่มีประวัติความเคลื่อนไหว</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>