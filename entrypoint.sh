#!/bin/bash
curl -o wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
chmod +x wait-for-it.sh
# Wait for MySQL to be ready
echo "Waiting for MySQL to start..."
./wait-for-it.sh db:3306 --timeout=30 --strict -- echo "MySQL is up!"

# Run migrations (or other tasks) before starting the app
echo "Running Laravel migrations..."
php artisan storage:link
php artisan migrate --force
php artisan db:seed
echo "running at http://localhost:8081"
php-fpm
