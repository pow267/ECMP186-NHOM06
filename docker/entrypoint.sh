#!/bin/sh

# Sửa lỗi permission trong Fly volume
chown -R www-data:www-data /var/www/html/public/assets/images 2>/dev/null || true

exec apache2-foreground