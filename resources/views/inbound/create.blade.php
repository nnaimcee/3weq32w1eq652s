<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📦 รับสินค้าเข้าคลัง (Inbound)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inbound.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">สแกนบาร์โค้ดสินค้า:</label>
                        <input type="text" name="barcode" id="barcode" required autofocus
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 border-2" 
                               placeholder="เอาเมาส์คลิกที่นี่แล้วยิงสแกนเนอร์...">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวน (Quantity):</label>
                        <input type="number" name="quantity" id="quantity" required min="1"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               placeholder="ระบุจำนวน">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">เลือกตำแหน่งจัดเก็บ (Location):</label>
                        <select name="location_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- กรุณาเลือก --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full text-lg">
                            💾 ยืนยันการรับเข้า
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('barcode').addEventListener('keypress', function(e) {
            // ถ้าพิมพ์เสร็จแล้วกด Enter (หรือเครื่องสแกนยิงมา)
            if (e.key === 'Enter') {
                e.preventDefault(); // ห้ามฟอร์มลั่น Submit
                document.getElementById('quantity').focus(); // ให้เคอร์เซอร์กระโดดไปช่อง "จำนวน" อัตโนมัติ!
            }
        });
    </script>
</x-app-layout>