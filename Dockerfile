# ใช้ PHP 8.2 พร้อม Apache เป็นพื้นฐาน
FROM php:8.4-apache

# ติดตั้งส่วนเสริมที่จำเป็นสำหรับ Laravel และ PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql gd

# ตั้งค่า Apache Rewrite Module
RUN a2enmod rewrite

# คัดลอกโค้ดทั้งหมดเข้าเครื่อง
COPY . /var/www/html

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ตั้งค่า Permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# เปลี่ยน Document Root ไปที่ /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# เปิดพอร์ต 80
EXPOSE 80