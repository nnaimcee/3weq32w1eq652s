<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7m0 0L9 4" />
            </svg>
            Warehouse Visual Map
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-lg mb-8 flex flex-wrap justify-center gap-6 border border-gray-200">
                <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-200">
                    <span class="w-4 h-4 rounded-full bg-green-500 shadow-sm"></span>
                    <span class="font-medium text-green-800">ว่าง (Empty)</span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full border border-blue-200">
                    <span class="w-4 h-4 rounded-full bg-blue-600 shadow-sm"></span>
                    <span class="font-medium text-blue-800">มีสินค้า (Occupied)</span>
                </div>
                <div class="flex items-center gap-2 bg-yellow-50 px-4 py-2 rounded-full border border-yellow-200">
                    <span class="w-4 h-4 rounded-full bg-yellow-500 shadow-sm"></span>
                    <span class="font-medium text-yellow-800">จองพื้นที่ (Reserved)</span>
                </div>
            </div>

            @foreach($zones as $zoneName => $locations)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-indigo-600 w-2 h-10 rounded-full"></div>
                    <h3 class="text-3xl font-bold text-gray-800">Zone: {{ $zoneName }}</h3>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-6">
                    @foreach($locations as $loc)
                        @php
                            $totalQty = $loc->stocks->sum('quantity');
                            $totalReserved = $loc->stocks->sum('reserved_qty');
                            
                            // กำหนดชุดสีใหม่ (ที่ดู Modern และเข้ากันมากขึ้น)
                            // Default: Empty (เขียวมิ้นต์อ่อน)
                            $cardClasses = 'bg-green-50 border-green-300 text-green-900 hover:bg-green-100 hover:border-green-400';
                            $dotColor = 'bg-green-500';

                            if ($totalQty > 0) {
                                // Occupied (น้ำเงินเข้ม)
                                $cardClasses = 'bg-blue-100 border-blue-300 text-blue-900 hover:bg-blue-200 hover:border-blue-400';
                                $dotColor = 'bg-blue-600';
                            } elseif ($totalReserved > 0) {
                                // Reserved (เหลืองทอง)
                                $cardClasses = 'bg-yellow-50 border-yellow-300 text-yellow-900 hover:bg-yellow-100 hover:border-yellow-400';
                                $dotColor = 'bg-yellow-500';
                            }
                        @endphp

                        <div class="relative group rounded-2xl p-5 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-2 {{ $cardClasses }} flex flex-col items-center justify-center h-36 cursor-pointer overflow-hidden">
                            
                            <span class="absolute top-3 right-3 w-3 h-3 rounded-full {{ $dotColor }} shadow-sm"></span>

                            <span class="text-xs font-bold uppercase tracking-wider opacity-70 mb-1">Bin: {{ $loc->bin }}</span>
                            <span class="text-4xl font-extrabold">{{ $loc->shelf }}</span>
                            
                            <div class="absolute z-20 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 bottom-full mb-3 left-1/2 transform -translate-x-1/2 w-48 bg-gray-900 text-white p-3 rounded-lg shadow-2xl text-sm pointer-events-none">
                                <div class="font-bold text-base mb-2 border-b border-gray-700 pb-1">{{ $loc->name }}</div>
                                <div class="space-y-1">
                                    <div class="flex justify-between"><span>📦 สินค้า:</span> <span class="font-bold text-green-400">{{ number_format($totalQty) }}</span></div>
                                    <div class="flex justify-between"><span>🏷️ จองแล้ว:</span> <span class="font-bold text-yellow-400">{{ number_format($totalReserved) }}</span></div>
                                    <div class="flex justify-between pt-1 border-t border-gray-700 mt-1"><span>สถานะ:</span> <span class="capitalize">{{ $loc->status }}</span></div>
                                </div>
                                <svg class="absolute text-gray-900 h-3 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
</x-app-layout>