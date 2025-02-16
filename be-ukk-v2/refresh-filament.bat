@echo off
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan filament:cache
php artisan optimize:clear
echo Filament cache refreshed!