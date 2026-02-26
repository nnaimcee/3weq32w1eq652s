<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">

                    {{-- 1. ภาพรวม --}}
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>📊 ภาพรวม</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">🏠 แดชบอร์ด</x-dropdown-link>
                            <x-dropdown-link :href="route('inventory.map')" :active="request()->routeIs('inventory.map')">🗺️ แผนผังคลังสินค้า</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    {{-- 2. สินค้า --}}
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>📦 สินค้า</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('inventory.index')" :active="request()->routeIs('inventory.index')">📋 สต็อกสินค้า</x-dropdown-link>
                            <x-dropdown-link :href="route('products.create')" :active="request()->routeIs('products.create')">➕ เพิ่มสินค้าใหม่</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    {{-- 3. งานคลัง (รวม scanner เป็นหลัก) --}}
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>🔄 งานคลัง</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('scanner.index')">📷 สแกนรับ/เบิกสินค้า</x-dropdown-link>
                            <x-dropdown-link :href="route('transfer.create')">🚚 ย้ายตำแหน่ง</x-dropdown-link>
                            <x-dropdown-link :href="route('transfer.pending')">📥 รอรับของ (Transit)</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                    {{-- 4. ประวัติ --}}
                    <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
                        {{ __('📜 ประวัติ') }}
                    </x-nav-link>

                    {{-- 5. แอดมิน (เฉพาะ admin) --}}
                    @if(auth()->user()->role === 'admin')
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>⚙️ แอดมิน</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('locations.index')">📍 จัดการสถานที่</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">📊 ภาพรวม</div>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">🏠 แดชบอร์ด</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('inventory.map')" :active="request()->routeIs('inventory.map')">🗺️ แผนผังคลัง</x-responsive-nav-link>

            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">📦 สินค้า</div>
            <x-responsive-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.index')">📋 สต็อกสินค้า</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.create')" :active="request()->routeIs('products.create')">➕ เพิ่มสินค้าใหม่</x-responsive-nav-link>

            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">🔄 งานคลัง</div>
            <x-responsive-nav-link :href="route('scanner.index')">📷 สแกนรับ/เบิกสินค้า</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transfer.create')">🚚 ย้ายตำแหน่ง</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transfer.pending')">📥 รอรับของ (Transit)</x-responsive-nav-link>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">📜 ประวัติธุรกรรม</x-responsive-nav-link>
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">⚙️ แอดมิน</div>
            <x-responsive-nav-link :href="route('locations.index')">📍 จัดการสถานที่</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>