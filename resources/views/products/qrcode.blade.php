<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Print QR Code - {{ $product->sku }}</title>
    <style>
        /* ตั้งค่าหน้ากระดาษให้เป็นขนาดสติกเกอร์ 50mm x 50mm */
        @page {
            size: 50mm 50mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50mm;
            width: 50mm;
            font-family: 'Inter', sans-serif;
        }

        .sticker {
            width: 50mm;
            height: 50mm;
            text-align: center;
            padding: 2mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .product-name {
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            margin-bottom: 4px;
        }

        .qr-container {
            margin-bottom: 4px;
        }

        .qr-container img {
            width: 30mm;
            height: 30mm;
            image-rendering: pixelated;
        }

        .sku-text {
            font-size: 10px;
            font-family: monospace;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="sticker">
        <div class="product-name">{{ $product->name }}</div>

        <div class="qr-container">
            @if($product->qr_code_image)
                <img src="{{ asset('storage/qrcodes/' . $product->qr_code_image) }}" alt="QR Code">
            @else
                {!! DNS2D::getBarcodeHTML($product->barcode ?: $product->sku, 'QRCODE', 4, 4) !!}
            @endif
        </div>

        <div class="sku-text">{{ $product->sku }}</div>
    </div>

</body>
</html>
