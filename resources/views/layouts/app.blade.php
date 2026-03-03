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
            /* Smooth sidebar transitions */
            aside { will-change: transform; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">

            {{-- Sidebar --}}
            @include('layouts.navigation')

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col min-h-screen lg:ml-64">

                {{-- Top Bar --}}
                <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
                    <div class="flex items-center justify-between px-4 sm:px-6 py-3">

                        {{-- Mobile toggle --}}
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition mr-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Page heading --}}
                        @isset($header)
                            <div class="flex-1">{{ $header }}</div>
                        @endisset

                        {{-- User menu --}}
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500 hidden sm:block font-medium">{{ Auth::user()->name }}</span>
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center justify-center w-9 h-9 rounded-full text-white text-sm font-bold hover:opacity-90 transition shadow"
                                        style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            โปรไฟล์
                                        </div>
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <div class="flex items-center gap-2 text-red-600">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                ออกจากระบบ
                                            </div>
                                        </x-dropdown-link>
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
            class="fixed inset-0 bg-slate-900 bg-opacity-40 z-40 lg:hidden backdrop-blur-sm"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:leave="transition-opacity ease-linear duration-200"
            style="display:none">
        </div>
    </body>
</html>
