#!/bin/bash
set -e

# Default port to 8080 or use Render's dynamic PORT
PORT="${PORT:-8080}"

# Configure Apache port dynamically across all config files
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-enabled/*.conf

# Ensure required storage and cache directories exist
mkdir -p /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Fix ownership and permissions for web server
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure .env exists
[ -f .env ] || cp .env.example .env

# Generate key if not provided
if [ -z "$APP_KEY" ]; then
    echo "Generating application encryption key..."
    php artisan key:generate --force || true
fi

# Clear stale caches
php artisan optimize:clear

# Create storage symlink
php artisan storage:link --force || true

# Run database migrations if database credentials are provided
if [ -n "$DB_HOST" ] || [ -n "$DB_URL" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Warning: Migration failed, check database connection."
fi

# Start background queue worker
echo "Starting background queue worker..."
(php artisan queue:work --sleep=3 --tries=2 --max-time=3600 &)

# Start Apache in the foreground
echo "Starting Apache on port ${PORT}..."
exec apache2-foreground
