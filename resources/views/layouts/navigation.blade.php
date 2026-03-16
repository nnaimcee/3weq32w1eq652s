{{-- Modern Minimal Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'"
    class="group fixed inset-y-0 left-0 z-50 bg-zinc-950 border-r border-zinc-800 transition-all duration-300 ease-in-out flex flex-col w-64 lg:w-20 hover:lg:w-64">

    {{-- Logo --}}
    <div class="h-16 flex items-center justify-center lg:justify-start px-0 lg:px-4 border-b border-zinc-800/80 flex-shrink-0 transition-all duration-300">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center lg:justify-start gap-4 w-full h-full px-5 lg:px-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center font-black text-xl flex-shrink-0 shadow-[0_0_15px_rgba(99,102,241,0.5)]">
                W
            </div>
            <div class="overflow-hidden transition-all duration-300 whitespace-nowrap opacity-100 lg:opacity-0 group-hover:lg:opacity-100 flex-1 hidden lg:block">
                <h1 class="font-bold text-zinc-100 tracking-tight text-lg">WMS</h1>
            </div>
        </a>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 py-5 px-3 space-y-1.5 overflow-x-hidden overflow-y-auto custom-scrollbar">

        {{-- ลิงก์ที่ 1 --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">แดชบอร์ด</span>
        </a>

        <a href="{{ route('inventory.map') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('inventory.map') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">แผนผังคลัง</span>
        </a>

        {{-- Divider --}}
        <div class="pt-5 pb-2 transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">
            <p class="px-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">สินค้า</p>
        </div>

        <a href="{{ route('inventory.index') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('inventory.index') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">สต็อกสินค้า</span>
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('products.create') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">เพิ่มสินค้าใหม่</span>
        </a>
        @endif

        {{-- Divider --}}
        <div class="pt-5 pb-2 transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">
            <p class="px-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">งานคลัง</p>
        </div>

        <a href="{{ route('scanner.index') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('scanner.index') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">สแกนรับ/เบิก</span>
        </a>

        <a href="{{ route('location-reservations.index') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('location-reservations.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">จองพื้นที่</span>
        </a>

        <a href="{{ route('transfer.create') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('transfer.create') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">ย้ายตำแหน่ง</span>
        </a>

        <a href="{{ route('transfer.pending') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('transfer.pending') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">รอรับของ</span>
        </a>

        {{-- Divider --}}
        <div class="pt-5 pb-2 transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">
            <p class="px-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">รายงาน</p>
        </div>

        <a href="{{ route('transactions.index') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('transactions.index') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">ประวัติธุรกรรม</span>
        </a>

        @if(auth()->user()->role === 'admin')
        {{-- Divider --}}
        <div class="pt-5 pb-2 transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">
            <p class="px-4 text-[10px] font-bold text-zinc-500 uppercase tracking-widest">แอดมิน</p>
        </div>

        <a href="{{ route('locations.index') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('locations.index') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">จัดการสถานที่</span>
        </a>

        <a href="{{ route('users.index') }}"
           class="flex items-center gap-4 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-zinc-200' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="whitespace-nowrap font-medium text-sm transition-opacity duration-300 opacity-100 lg:opacity-0 group-hover:lg:opacity-100 lg:hidden group-hover:lg:block">จัดการผู้ใช้งาน</span>
        </a>
        @endif

    </nav>
</aside>