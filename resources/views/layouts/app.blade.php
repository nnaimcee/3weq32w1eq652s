<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex bg-gray-100">

            {{-- Sidebar --}}
            @include('layouts.navigation')

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
                {{-- Top Bar --}}
                <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
                    <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                        {{-- Mobile toggle --}}
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700 mr-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Page heading --}}
                        @isset($header)
                            <div class="flex-1">{{ $header }}</div>
                        @endisset

                        {{-- User menu --}}
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-500 hidden sm:block">{{ Auth::user()->name }}</span>
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-white text-sm font-bold hover:bg-slate-800 transition">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">⚙️ โปรไฟล์</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">🚪 ออกจากระบบ</x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:leave="transition-opacity ease-linear duration-200" style="display:none"></div>
    </body>
</html>
