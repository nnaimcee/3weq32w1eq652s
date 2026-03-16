<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $product->sku }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* ===== Print Styles ===== */
        @media print {
            @page {
                size: 50mm 30mm;
                margin: 0;
            }

            body {
                background: white;
                display: block;
                min-height: unset;
                padding: 0;
                margin: 0;
            }

            /* Hide everything else */
            .np-print-hide {
                display: none !important;
            }

            /* Show only sticker */
            .print-sticker {
                display: flex !important;
                width: 50mm;
                height: 30mm;
                padding: 2mm;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                background: white;
            }

            .print-sticker .product-name {
                font-size: 10px;
                font-weight: bold;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                width: 100%;
                margin-bottom: 2px;
                line-height: 1.2;
            }

            .print-sticker .barcode-container {
                margin-bottom: 2px;
            }

            .print-sticker .sku-text {
                font-size: 10px;
                font-family: monospace;
                font-weight: bold;
                line-height: 1;
            }
        }

        /* Hidden on screen, shown at print */
        .print-sticker {
            display: none;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Abstract Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-indigo-100/40 via-purple-50/40 to-blue-100/40 -z-10 np-print-hide"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-lg h-[400px] bg-white/40 blur-3xl -z-10 rounded-full np-print-hide"></div>

    {{-- Screen Preview --}}
    <div class="w-full max-w-[420px] np-print-hide">
        
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-200 text-2xl mb-3">🏷️</div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">พิมพ์สติกเกอร์บาร์โค้ด</h1>
            <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Barcode Printer</p>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-6 mb-4 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-50 to-purple-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>

            <div class="flex items-center justify-center mb-6">
                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100/50">ตัวอย่างสติกเกอร์ (50×30mm)</span>
            </div>

            <div class="flex justify-center mb-8">
                <!-- Inner Sticker Preview -->
                <div class="bg-white rounded-xl p-4 border-2 border-dashed border-slate-200 shadow-sm w-[220px] relative">
                    <div class="absolute -top-3 -right-3 w-6 h-6 bg-slate-100 rounded-full border border-white flex items-center justify-center shadow-sm"><span class="w-2 h-2 bg-slate-300 rounded-full"></span></div>
                    <div class="absolute -bottom-3 -left-3 w-6 h-6 bg-slate-100 rounded-full border border-white flex items-center justify-center shadow-sm"><span class="w-2 h-2 bg-slate-300 rounded-full"></span></div>
                    
                    <div class="text-center">
                        <div class="text-[11px] font-bold text-slate-800 truncate w-full mb-1.5">{{ $product->name }}</div>
                        <div class="flex justify-center my-1 opacity-80 mix-blend-multiply">
                            {!! DNS1D::getBarcodeHTML($product->barcode ?: $product->sku, 'C128', 1.5, 33) !!}
                        </div>
                        <div class="text-[10px] font-mono font-bold text-slate-600 mt-1">{{ $product->barcode ?: $product->sku }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100 space-y-3">
                <div class="flex justify-between items-center text-sm border-b border-slate-100/50 pb-2">
                    <span class="text-slate-500 font-medium">ชื่อสินค้า</span>
                    <span class="font-bold text-slate-800 text-right max-w-[60%] truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 25) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-slate-100/50 pb-2">
                    <span class="text-slate-500 font-medium">SKU</span>
                    <span class="font-mono font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm text-xs">{{ $product->sku }}</span>
                </div>
                @if($product->barcode && $product->barcode !== $product->sku)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Barcode</span>
                    <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 shadow-sm text-xs">{{ $product->barcode }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            <button onclick="window.print()" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-slate-800/20 transition-all hover:shadow-xl hover:-translate-y-0.5 flex flex-col items-center justify-center gap-1 group">
                <div class="flex items-center gap-2 text-base">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    พิมพ์สติกเกอร์บาร์โค้ด
                </div>
                <span class="text-[10px] text-slate-400 font-medium tracking-wide">คลิกเพื่อสั่งปริ้นท์ผ่านเบราว์เซอร์</span>
            </button>
            <a href="javascript:history.back()" class="w-full bg-white hover:bg-slate-50 text-slate-600 font-bold py-3.5 px-6 rounded-2xl shadow-sm border border-slate-200 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 text-sm">
                ← ย้อนกลับ
            </a>
        </div>
    </div>

    {{-- Actual Print Content (Will only show when printing) --}}
    <div class="print-sticker">
        <div class="product-name">{{ $product->name }}</div>
        <div class="barcode-container">
            {!! DNS1D::getBarcodeHTML($product->barcode ?: $product->sku, 'C128', 1.5, 33) !!}
        </div>
        <div class="sku-text">{{ $product->barcode ?: $product->sku }}</div>
    </div>

</body>
</html>