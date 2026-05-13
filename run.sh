#!/usr/bin/env bash
set -e

php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
npm run build
php artisan serve
