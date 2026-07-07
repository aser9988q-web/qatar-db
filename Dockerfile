FROM php:8.2-apache

# تثبيت الإضافات اللازمة لاتصال قاعدة البيانات PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# تفعيل مود الـ Rewrite الخاص بأباتشي
RUN a2enmod rewrite

# تغيير DocumentRoot ليكون مجلد المجلد الرئيسي للمشروع ليعمل .htaccess
# أو توجيهه مباشرة لـ public إذا كان هذا هو المطلوب
# سنقوم بضبطه ليكون المجلد الرئيسي مع السماح بالـ Overrides
RUN sed -i 's|/var/www/html|/var/www/html|g' /etc/apache2/sites-available/0000-default.conf
RUN echo '<Directory /var/www/html/> \n\
    Options Indexes FollowSymLinks \n\
    AllowOverride All \n\
    Require all granted \n\
</Directory>' >> /etc/apache2/apache2.conf

# نسخ ملفات موقعك داخل السيرفر
COPY . /var/www/html/

# تأكيد الصلاحيات
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
