<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WMS System') }} — Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }

            /* ===== Mobile / Tablet dark gradient ===== */
            @media (max-width: 1023px) {
                body {
                    background: linear-gradient(145deg, #1e3a5f 0%, #2d4a8a 55%, #3b6cb7 100%) !important;
                }
                .mobile-form-panel {
                    background: transparent !important;
                    box-shadow: none !important;
                }
                /* All text → white on dark bg */
                .mobile-form-panel h1, .mobile-form-panel h2, .mobile-form-panel h3,
                .mobile-form-panel p, .mobile-form-panel label,
                .mobile-form-panel span, .mobile-form-panel div {
                    color: rgba(255,255,255,0.92) !important;
                }
                .mobile-form-panel a { color: #93c5fd !important; }
                /* Frosted-glass inputs */
                .mobile-form-panel input[type="email"],
                .mobile-form-panel input[type="password"],
                .mobile-form-panel input[type="text"] {
                    background: rgba(255,255,255,0.12) !important;
                    border: 1px solid rgba(255,255,255,0.25) !important;
                    color: #ffffff !important;
                }
                .mobile-form-panel input::placeholder { color: rgba(255,255,255,0.40) !important; }
                .mobile-form-panel svg { stroke: rgba(255,255,255,0.45) !important; }
                .mobile-form-panel .border-t { border-color: rgba(255,255,255,0.15) !important; }
                .mobile-glow-1, .mobile-glow-2 { display: block !important; }
            }
            .mobile-glow-1, .mobile-glow-2 { display: none; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex relative overflow-hidden">

            <!-- Mobile glow blobs -->
            <div class="mobile-glow-1 pointer-events-none fixed rounded-full"
                 style="top:-8rem; left:-8rem; width:22rem; height:22rem; background:#2563eb; filter:blur(100px); opacity:0.22; z-index:0;"></div>
            <div class="mobile-glow-2 pointer-events-none fixed rounded-full"
                 style="bottom:-6rem; right:-6rem; width:18rem; height:18rem; background:#6366f1; filter:blur(90px); opacity:0.22; z-index:0;"></div>

            <!-- ===== Left Panel (Desktop only) ===== -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center"
                 style="background-color: #0f172a;">

                <!-- Gradient overlay -->
                <div class="absolute inset-0"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e3a8a 100%);"></div>

                <!-- Glow blobs -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                    <div class="absolute rounded-full"
                         style="top:-10rem; left:-16rem; width:24rem; height:24rem; background:#2563eb; filter:blur(100px); opacity:0.30;"></div>
                    <div class="absolute rounded-full"
                         style="top:50%; left:50%; width:500px; height:500px; background:#6366f1; filter:blur(120px); opacity:0.20; transform:translate(-50%,-50%);"></div>
                    <div class="absolute rounded-full"
                         style="bottom:0; right:0; width:20rem; height:20rem; background:#06b6d4; filter:blur(100px); opacity:0.20;"></div>
                </div>

                <!-- Branding -->
                <div class="relative z-10 p-12 text-center text-white flex flex-col items-center">
                    <!-- Logo badge -->
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-8 border shadow-2xl"
                         style="background: rgba(255,255,255,0.10); border-color: rgba(255,255,255,0.18); backdrop-filter: blur(12px);">
                        <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>

                    <h1 class="text-4xl font-extrabold tracking-tight mb-4" style="letter-spacing:-0.02em;">WMS System</h1>
                    <p class="text-slate-300 text-base max-w-xs leading-relaxed">ระบบจัดการคลังสินค้า</p>

                    <!-- Feature pills -->
                    <div class="mt-10 flex flex-wrap justify-center gap-3">
                        <span class="px-4 py-1.5 rounded-full text-xs font-semibold"
                              style="background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.15);">📦 สต็อกสินค้า</span>
                        <span class="px-4 py-1.5 rounded-full text-xs font-semibold"
                              style="background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.15);">🗺️ แผนผังคลัง</span>
                        <span class="px-4 py-1.5 rounded-full text-xs font-semibold"
                              style="background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.15);">📊 รายงาน</span>
                    </div>

                    <div class="mt-16 text-xs text-slate-500">
                        &copy; {{ date('Y') }} WMS System. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- ===== Right Panel (Form) ===== -->
            <div class="mobile-form-panel w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-20 bg-white z-10 relative">

                <!-- Mobile Logo -->
                <div class="lg:hidden w-14 h-14 rounded-2xl flex items-center justify-center mb-6 shadow-lg"
                     style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
