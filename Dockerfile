FROM php:8.2-apache

# تثبيت الإضافات اللازمة لاتصال قاعدة البيانات PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# تفعيل مود الـ Rewrite الخاص بأباتشي
RUN a2enmod rewrite

# نسخ ملفات موقعك داخل السيرفر
COPY . /var/www/html/

EXPOSE 80
