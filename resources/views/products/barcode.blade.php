<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $product->sku }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        /* ===== Screen Styles (Preview) ===== */
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .screen-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 16px;
        }

        .page-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
            text-align: center;
        }

        .preview-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
            padding: 24px;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .preview-label {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        /* The actual sticker preview */
        .sticker-preview {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 12px 16px;
            width: 220px;
            text-align: center;
            background: white;
        }

        .sticker-preview .product-name {
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            margin-bottom: 6px;
            color: #111827;
        }

        .sticker-preview .barcode-container {
            margin-bottom: 4px;
            overflow: hidden;
        }

        .sticker-preview .sku-text {
            font-size: 10px;
            font-family: monospace;
            font-weight: 700;
            color: #374151;
        }

        .product-info-box {
            width: 100%;
            background: #f9fafb;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 16px;
        }

        .product-info-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 4px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .product-info-row:last-child {
            border-bottom: none;
        }

        .product-info-label { color: #6b7280; }
        .product-info-value { font-weight: 600; color: #111827; }

        .btn-print {
            display: block;
            width: 100%;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            text-align: center;
        }

        .btn-print:hover { background: #1e40af; }

        .btn-back {
            display: block;
            width: 100%;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            text-align: center;
            text-decoration: none;
        }

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
            }

            .screen-wrapper,
            .page-title,
            .preview-label,
            .product-info-box,
            .btn-print,
            .btn-back {
                display: none !important;
            }

            .print-sticker {
                display: flex !important;
                width: 50mm;
                height: 30mm;
                padding: 2mm;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
            }

            .print-sticker .product-name {
                font-size: 10px;
                font-weight: bold;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                width: 100%;
                margin-bottom: 2px;
            }

            .print-sticker .barcode-container {
                margin-bottom: 2px;
            }

            .print-sticker .sku-text {
                font-size: 10px;
                font-family: monospace;
                font-weight: bold;
            }
        }

        /* Hidden on screen, shown at print */
        .print-sticker {
            display: none;
        }
    </style>
</head>
<body>

    {{-- Screen Preview --}}
    <div class="screen-wrapper">
        <div class="page-title">🏷️ พิมพ์บาร์โค้ด</div>

        <div class="preview-card">
            <div class="preview-label">ตัวอย่างสติกเกอร์ (50×30mm)</div>

            <div class="sticker-preview">
                <div class="product-name">{{ $product->name }}</div>
                <div class="barcode-container">
                    {!! DNS1D::getBarcodeHTML($product->barcode ?: $product->sku, 'C128', 1.5, 33) !!}
                </div>
                <div class="sku-text">{{ $product->sku }}</div>
            </div>

            <div class="product-info-box">
                <div class="product-info-row">
                    <span class="product-info-label">ชื่อสินค้า</span>
                    <span class="product-info-value">{{ Str::limit($product->name, 25) }}</span>
                </div>
                <div class="product-info-row">
                    <span class="product-info-label">SKU</span>
                    <span class="product-info-value">{{ $product->sku }}</span>
                </div>
                @if($product->barcode)
                <div class="product-info-row">
                    <span class="product-info-label">Barcode</span>
                    <span class="product-info-value">{{ $product->barcode }}</span>
                </div>
                @endif
            </div>
        </div>

        <button class="btn-print" onclick="window.print()">🖨️ พิมพ์บาร์โค้ด</button>
        <a href="javascript:history.back()" class="btn-back">← กลับ</a>
    </div>

    {{-- Actual Print Content --}}
    <div class="print-sticker">
        <div class="product-name">{{ $product->name }}</div>
        <div class="barcode-container">
            {!! DNS1D::getBarcodeHTML($product->barcode ?: $product->sku, 'C128', 1.5, 33) !!}
        </div>
        <div class="sku-text">{{ $product->sku }}</div>
    </div>

</body>
</html>