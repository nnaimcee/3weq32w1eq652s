{{-- Dark Sidebar Navigation --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800 text-white transform lg:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col">

    {{-- Logo / Brand --}}
    <div class="px-6 py-5 border-b border-slate-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow-lg">
                W
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">WMS System</h1>
                <p class="text-xs text-slate-400">Warehouse Management</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        {{-- เมนูหลัก --}}
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">เมนูหลัก</p>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">🏠</span> แดชบอร์ด
        </a>

        <a href="{{ route('inventory.map') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('inventory.map') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">🗺️</span> แผนผังคลัง
        </a>

        {{-- สินค้า --}}
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">สินค้า</p>

        <a href="{{ route('inventory.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('inventory.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">📦</span> สต็อกสินค้า
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('products.create') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">➕</span> เพิ่มสินค้าใหม่
        </a>
        @endif

        {{-- งานคลัง --}}
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">งานคลัง</p>

        <a href="{{ route('scanner.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('scanner.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">📷</span> สแกนรับ/เบิก
        </a>

        <a href="{{ route('transfer.create') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('transfer.create') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">🚚</span> ย้ายตำแหน่ง
        </a>

        <a href="{{ route('transfer.pending') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('transfer.pending') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">📥</span> รอรับของ (Transit)
        </a>

        {{-- รายงาน --}}
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">รายงาน</p>

        <a href="{{ route('transactions.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('transactions.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">📜</span> ประวัติธุรกรรม
        </a>

        {{-- แอดมิน --}}
        @if(auth()->user()->role === 'admin')
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2">แอดมิน</p>

        <a href="{{ route('locations.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('locations.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">🏢</span> จัดการสถานที่
        </a>

        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="w-5 text-center">👥</span> จัดการผู้ใช้งาน
        </a>
        @endif

    </nav>

    {{-- Bottom User Info --}}
    <div class="border-t border-slate-700 px-4 py-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</aside>