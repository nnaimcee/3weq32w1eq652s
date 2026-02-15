<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $product->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; margin-top: 50px; }
        
        /* กรอบจำลองขนาดสติกเกอร์ */
        .label-box {
            border: 2px dashed #ccc;
            padding: 20px;
            display: inline-block;
            border-radius: 8px;
            background-color: white;
        }

        /* ปุ่มสั่งพิมพ์ */
        .btn-print {
            background-color: #2563eb; color: white; padding: 10px 20px;
            border: none; border-radius: 5px; font-size: 16px; cursor: pointer;
            margin-bottom: 20px;
        }

        /* CSS สำหรับตอนสั่งพิมพ์จริง (ซ่อนปุ่ม และเอากรอบออก) */
        @media print {
            .no-print { display: none !important; }
            .label-box { border: none; margin: 0; padding: 0; }
            body { margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ พิมพ์สติกเกอร์บาร์โค้ด</button>
        <br><br>
        <a href="/inventory" style="color: #4b5563; text-decoration: none;">&larr; กลับไปหน้ารายการสินค้า</a>
    </div>

    <div class="label-box">
        <h3 style="margin: 0 0 10px 0; font-size: 18px;">{{ $product->name }}</h3>
        <svg id="barcode"></svg>
        <p style="margin: 5px 0 0 0; font-size: 14px; color: #555;">SKU: {{ $product->barcode }}</p>
    </div>

    <script>
        JsBarcode("#barcode", "{{ $product->barcode }}", {
            format: "CODE128", // ฟอร์แมตยอดนิยมที่อ่านง่ายที่สุด
            lineColor: "#000",
            width: 2,          // ความหนาของเส้น
            height: 60,        // ความสูง
            displayValue: true // ให้แสดงตัวเลขบาร์โค้ดข้างใต้ด้วย
        });
    </script>

</body>
</html>