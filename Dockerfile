FROM php:8.4-apache

# 1. ติดตั้ง PHP Extensions และ Node.js (เพิ่มคำสั่งติดตั้ง Node.js 20.x หรือ 22.x)
RUN apt-get update && apt-get install -y \
    libpq-dev libpng-dev libzip-dev libonig-dev libxml2-dev \
    zip unzip git curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql gd zip mbstring bcmath xml

# 2. เปิดใช้งาน Apache Rewrite Module
RUN a2enmod rewrite

# 3. คัดลอกโค้ดทั้งหมด
COPY . /var/www/html

# 4. ติดตั้ง Composer (PHP Dependencies)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 5. ติดตั้ง NPM และ Build Assets (CSS/JS)
# ขั้นตอนนี้จะสร้างไฟล์ public/build/manifest.json ที่ระบบกำลังถามหาครับ
RUN npm install
RUN npm run build

# 6. ตั้งค่า Apache VirtualHost (เหมือนเดิม)
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 7. ตั้งค่า Permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. สั่งรัน Migration และเริ่ม Apache
CMD php artisan migrate --force && apache2-foreground

EXPOSE 80