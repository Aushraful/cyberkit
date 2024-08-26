#!/bin/sh

cd /var/www/cyberkit

# coping .env.local to .env
cp .env.example .env

echo "Generating application encryption key..."
php artisan key:generate
echo "Done."

echo "Linking storage..."
php artisan storage:link
echo "Done."

echo "Ensure correct ownership..."
chown -R HOST_USR:HOST_USR /var/www/cyberkit
echo "Done."

# echo "Setting storage permission..."
# chown -R www-data:www-data storage
# chmod -R 775 storage
# echo "Done."

echo "Setting storage and cache permissions..."
chown -R www-data:www-data storage
chmod -R 775 storage bootstrap/cache
echo "Done."

echo "Cleaning cache files..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "Done."

if test -f /vendor; then
	echo "Removing vendor..."
	rm -rf vendor
	echo "Done."
fi

echo "Doing a composer install..."
composer install --ignore-platform-reqs
echo "Done."

echo "executing migration..."
php artisan migrate
composer dump-autoload
php artisan db:seed
echo "Done."
