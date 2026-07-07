FROM php:8.2-apache

# تثبيت الإضافات اللازمة لاتصال قاعدة البيانات PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# تفعيل مود الـ Rewrite الخاص بأباتشي
RUN a2enmod rewrite

# تغيير DocumentRoot ليكون مجلد public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# نسخ ملفات موقعك داخل السيرفر
COPY . /var/www/html/

# تأكيد الصلاحيات
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
