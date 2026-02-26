<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WMS System') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50">
        <div class="min-h-screen flex">
            <!-- Left Panel (Branding / Image) -->
            <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative overflow-hidden items-center justify-center">
                <!-- Decorative elements -->
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 opacity-90"></div>
                
                <!-- Abstract circles -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                    <div class="absolute -top-40 -left-64 w-96 h-96 rounded-full bg-blue-600 blur-[100px] opacity-30"></div>
                    <div class="absolute top-1/2 left-1/2 w-[500px] h-[500px] rounded-full bg-indigo-500 blur-[120px] opacity-20 transform -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-cyan-500 blur-[100px] opacity-20"></div>
                </div>

                <div class="relative z-10 p-12 text-center text-white flex flex-col items-center">
                    <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/20 shadow-2xl">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h1 class="text-5xl font-extrabold tracking-tight mb-6 drop-shadow-lg">WMS System</h1>
                    <p class="text-lg text-slate-300 max-w-md mx-auto leading-relaxed">
                        ระบบจัดการคลังสินค้าอัจฉริยะ ควบคุมสต็อก แม่นยำ รวดเร็ว และมีประสิทธิภาพ
                    </p>

                    <div class="mt-16 flex items-center justify-center gap-2 text-sm text-slate-400">
                        <span>&copy; {{ date('Y') }} WMS System. All rights reserved.</span>
                    </div>
                </div>
            </div>

            <!-- Right Panel (Form) -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 lg:p-24 bg-white shadow-2xl z-10 relative">
                <!-- Mobile Logo (visible only on small screens) -->
                <div class="lg:hidden w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
