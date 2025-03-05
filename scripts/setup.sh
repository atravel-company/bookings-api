#!/bin/bash

# Install npm dependencies
npm install

# Install Composer dependencies
composer install --no-interaction --optimize-autoloader

# Copy .env.example to .env if .env doesn't exist
if [ ! -f ".env" ]; then
  cp .env.example .env  
  php artisan key:generate
  echo ".env file created from .env.example"
fi

# Check if MySQL is running on port 3306
if nc -z mysql 3306; then
  echo "MySQL is running on port 3306"
  # Run migrations
  php artisan migrate
  php artisan db:seed
  echo "Migrations completed."
else
  echo "MySQL is not running on port 3306. Skipping migrations."
  echo "Please ensure your database is running and accessible."
  exit 1
fi