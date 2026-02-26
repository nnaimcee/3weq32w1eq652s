# Warehouse Management System (WMS)

ระบบจัดการคลังสินค้าอัจฉริยะ (WMS) พัฒนาด้วย **Laravel 11** และ **Tailwind CSS** ระบบออกแบบมาให้ใช้งานง่าย รวดเร็ว และรองรับฟีเจอร์ครบถ้วนสำหรับการบริหารจัดการสต็อกสินค้าในคลัง

![WMS System](https://raw.githubusercontent.com/nnaimcee/wms-system/main/public/images/dashboard-preview.png) *(เพิ่มรูปรถเข็นหรือภาพหน้า Dashboard จริงที่นี่)*

---

## 🌟 ฟีเจอร์หลัก (Key Features)

- **🔐 ระบบจัดการสิทธิ์ผู้ใช้งาน (Role-based Access Control)**
  - `Admin`: เข้าถึงได้ทุกฟังก์ชัน (เพิ่ม/ลบสินค้า, จัดการสถานที่, จอง/ปลดจอง, ลบสต็อก)
  - `Staff`: สิทธิ์การใช้งานจำกัด (รับของเข้า, เบิกของออก, ย้ายสถานที่, ดูรายงาน)

- **📦 การจัดการสินค้าคงคลัง (Inventory Management)**
  - ติดตามยอดสต็อกปัจจุบันแบบ Real-time
  - รองรับระบบ สินค้าแบบล็อต (Lot Number) และระบบเข้าก่อนออกก่อน (FIFO)
  - ระบบแจ้งเตือนสินค้าใกล้หมด (Low Stock Alert)

- **📍 การจัดการสถานที่ (Location & Zone Management)**
  - แบ่งสถานที่เก็บสินค้าเป็นโซน (Zones), ชั้นวาง (Shelves), และช่องเก็บ (Bins)
  - แผนผังคลังสินค้า (Warehouse Map) แบบ Visual แสดงจุดสถานะ (มีสินค้า 🔵, จอง 🟡, ว่าง 🟢, ปิดใช้งาน 🔴)
  - กำหนดสถานะพื้นที่ (Active / Inactive)

- **🔄 ระบบจัดการธุรกรรม (Transactions)**
  - **Inbound (รับเข้า):** สแกนรับของเข้าคลัง พร้อมสร้าง Lot อัตโนมัติ
  - **Outbound (เบิกออก):** เบิกของออกตามระบบ FIFO โดยอัตโนมัติ
  - **Transfer (ย้ายสถานที่):** ย้ายสินค้าข้ามโซน/ชั้นวาง พร้อมระบบตะกร้าพักของชั่วคราว (Transit)
  - **Reservation (ระบบจอง):** พนักงานขาย/แอดมินสามารถจองสินค้าล่วงหน้าได้ (ตัดยอดพร้อมจ่าย)

- **📱 ระบบสแกนเนอร์บาร์โค้ด (Barcode / QR Scanner)**
  - สร้างและพิมพ์ Barcode/QR Code สติ๊กเกอร์ได้จากระบบทันที
  - หน้าสแกนเนอร์เฉพาะสำหรับจัดการ รับเข้า/เบิกออก ที่หน้างานจริง

- **📊 แดชบอร์ดและรายงาน (Dashboard & Reporting)**
  - สรุปตัวเลขสำคัญ: สินค้าทั้งหมด, ยอดรวมในคลัง, ยอดจอง, สินค้าใน Transit
  - Chart.js: กราฟแท่งแสดงรายการเคลื่อนไหว 7 วันล่าสุด (รับ/เบิก/ย้าย/จอง)
  - Chart.js: กราฟโดนัทแสดงสัดส่วนสต็อกแยกตาม Zone

---

## 💻 เทคโนโลยีที่ใช้ (Tech Stack)

- **Backend:** PHP 8.2+, Laravel 11.x
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js (ผ่าน Laravel Breeze)
- **Database:** MySQL 8.0+
- **Charts:** Chart.js
- **Environment:** Docker (Laravel Sail)

---

## 🚀 การติดตั้ง (Installation)

โปรเจกต์นี้ทำงานผ่าน Laravel Sail (Docker) เพื่อความสะดวกในการเซตแอป Environment

### ความต้องการของระบบ (Prerequisites)
- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- WSL2 (สำหรับผู้ใช้ Windows)
- Git

### ขั้นตอนการรันโปรเจกต์ (Steps to run)

1. **Clone repository:**
   ```bash
   git clone https://github.com/nnaimcee/wms-system.git
   cd wms-system
   ```

2. **คัดลอกไฟล์ Environment:**
   ```bash
   cp .env.example .env
   ```

3. **ติดตั้ง Dependencies ผ่าน Docker:**
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
   ```

4. **รัน Laravel Sail:**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Generate App Key และ Migrate Database:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate --seed
   ```

6. **Build Frontend Assets:**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```

7. **เข้าใช้งานระบบ:**
   - เปิดเบราว์เซอร์ไปที่: `http://localhost/login`

---

## 🔑 ข้อมูลสำหรับทดสอบ (Test Credentials)

ระบบได้เตรียมข้อมูลผู้ใช้เริ่มต้นไว้ 2 ระดับ:

**Admin User**
- **Email:** `admin@wms.com`
- **Password:** `password`

**Staff User**
- **Email:** `staff@wms.com`
- **Password:** `password`

---

## 📝 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
This project is for educational and internal use.
