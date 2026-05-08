#!/bin/bash

# BismillahV2 Deployment Script
# Untuk hosting production

echo "🚀 Starting BismillahV2 Deployment..."

# 1. Update Composer Dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 2. Generate Application Key
echo "🔑 Generating application key..."
php artisan key:generate

# 3. Clear and Cache Configuration
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Cache Configuration for Production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 6. Create Storage Link
echo "🔗 Creating storage link..."
php artisan storage:link

# 7. Set Permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Install Node Dependencies (if needed)
echo "📦 Installing Node dependencies..."
npm install
npm run build

echo "✅ Deployment completed successfully!"
echo "🌐 Your Laravel application is ready for production!"
