<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Print Barcode - {{ $product->sku }}</title>
    <style>
        /* ตั้งค่าหน้ากระดาษให้เป็นขนาดสติกเกอร์ 50mm x 30mm */
        @page {
            size: 50mm 30mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 30mm;
            width: 50mm;
            font-family: 'Inter', sans-serif;
        }

        /* ตกแต่งให้เหมือนหน้าเพิ่มสินค้า */
        .sticker {
            width: 50mm;
            height: 30mm;
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
            margin-bottom: 2px;
        }

        .barcode-container {
            margin-bottom: 2px;
        }

        .sku-text {
            font-size: 10px;
            font-family: monospace;
            font-weight: bold;
        }

        /* ซ่อนปุ่มตอนสั่งพิมพ์ */
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

        <div class="barcode-container">
            {!! DNS1D::getBarcodeHTML($product->barcode ?: $product->sku, 'C128', 1.5, 33) !!}
        </div>

        <div class="sku-text">{{ $product->sku }}</div>
    </div>

</body>
</html>