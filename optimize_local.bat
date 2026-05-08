@echo off
echo 🚀 Optimizing Laravel for Local Development...

echo 📦 Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ⚡ Caching for better performance...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo 🗄️ Optimizing autoloader...
composer dump-autoload --optimize

echo ✅ Optimization completed!
echo 🌐 Your Laravel app should be faster now!
pause
