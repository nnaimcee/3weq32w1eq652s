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

    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">

            {{-- Sidebar --}}
            @include('layouts.navigation')

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col min-h-screen lg:ml-20 transition-all duration-300 w-full relative">

                {{-- Top Bar --}}
                <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200/70 sticky top-0 z-40 transition-all duration-300 shadow-sm">
                    <div class="flex items-center justify-between px-4 sm:px-6 h-16">

                        {{-- Left items (Mobile Toggle & Breadcrumb) --}}
                        <div class="flex items-center gap-4">
                            <button @click="sidebarOpen = !sidebarOpen"
                                class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            @isset($header)
                                <div class="font-bold text-slate-800 text-lg tracking-tight hidden sm:block border-l-2 border-indigo-500 pl-3 leading-none">{{ $header }}</div>
                            @endisset
                        </div>

                        {{-- Right items (User menu) --}}
                        <div class="flex items-center gap-4">
                            {{-- Global Search Toggle (Visual Only) --}}
                            <button class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-100/80 hover:bg-slate-200/80 text-slate-400 hover:text-slate-600 rounded-lg text-sm transition-colors border border-transparent focus:border-slate-300 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span>Search...</span>
                                <kbd class="ml-2 px-1.5 py-0.5 text-[10px] font-sans bg-white border border-slate-200 rounded text-slate-400">Ctrl K</kbd>
                            </button>

                            {{-- Notification Bell --}}
                            <button class="p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors relative">
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 border-2 border-white"></span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-2 hover:bg-slate-100 p-1 pr-3 rounded-full transition-colors border border-transparent hover:border-slate-200 focus:outline-none">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-black shadow-sm">
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="px-4 py-3 border-b border-gray-100 sm:hidden">
                                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs font-medium text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-slate-50 hover:text-indigo-600 focus:bg-slate-50 font-medium">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            จัดการโปรไฟล์
                                        </div>
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-rose-50 hover:text-rose-600 focus:bg-rose-50 font-medium">
                                            <div class="flex items-center gap-2 text-rose-500 group-hover:text-rose-600">
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
            class="fixed inset-0 bg-zinc-900/60 z-40 lg:hidden backdrop-blur-sm"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:leave="transition-opacity ease-linear duration-200"
            style="display:none">
        </div>
    </body>
</html>
