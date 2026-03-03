FROM php:8.4-apache

# ติดตั้ง PHP Extensions ที่จำเป็น
RUN apt-get update && apt-get install -y \
    libpq-dev libpng-dev libzip-dev libonig-dev libxml2-dev \
    zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql gd zip mbstring bcmath xml

# เปิดใช้งาน Apache Rewrite Module (สำคัญมากสำหรับ Route Laravel)
RUN a2enmod rewrite

# คัดลอกโค้ดทั้งหมด
COPY . /var/www/html

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader


RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# ตั้งค่า Permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# สั่งรัน Migration และเริ่ม Apache เมื่อเปิดเครื่อง
# (ใช้คำสั่งแบบรวบยอดเพื่อให้ทำงานทันทีที่ Deploy เสร็จ)
CMD php artisan migrate --force && apache2-foreground

EXPOSE 80