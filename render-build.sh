#!/usr/bin/env bash
# สั่งให้หยุดทำงานทันทีหากมี Error
set -o errexit

# ติดตั้ง dependencies (ถ้าไม่ได้ทำใน Dockerfile)
composer install --no-dev --optimize-autoloader

# รัน Migration (ใช้ --force เพราะเป็น Production)
php artisan migrate --force

# เคลียร์ Cache เพื่อประสิทธิภาพ
php artisan config:cache
php artisan route:cache
php artisan view:cache