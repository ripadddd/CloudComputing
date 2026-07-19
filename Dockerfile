# ============================================================
# Nama : Muhammad Rizki Padillah
# NPM  : 2410010252
# Tugas: Tugas 2 Cloud - Dockerfile
# ============================================================

# Base image: PHP 8.2 dengan Apache web server
# php:8.2-apache sudah include Apache + PHP sekaligus
FROM php:8.2-apache

# Label metadata image
LABEL maintainer="Muhammad Rizki Padillah"
LABEL npm="2410010252"
LABEL description="Tugas 2 Cloud - PHP To-Do List App"

# Set working directory di dalam container
WORKDIR /var/www/html

# Copy seluruh isi folder app/ ke dalam container
# Folder tujuan /var/www/html adalah root Apache
COPY app/ /var/www/html/

# Aktifkan modul Apache yang dibutuhkan
# mod_rewrite untuk URL rewriting (jika diperlukan)
RUN a2enmod rewrite

# Beri izin yang sesuai pada folder web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 (port default HTTP Apache)
# Port ini yang akan diteruskan oleh docker-compose
EXPOSE 80

# Perintah default saat container dijalankan
# apache2-foreground = jalankan Apache di foreground agar container tidak langsung berhenti
CMD ["apache2-foreground"]
