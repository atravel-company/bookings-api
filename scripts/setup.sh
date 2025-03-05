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
  echo "Starting migration and seeding..."
  # Run migrations
  php artisan migrate:fresh
  php artisan db:seed
  echo "Migrations completed."
else
  echo "MySQL is not running on port 3306. Skipping migrations and seeding."
  echo "Please ensure your database is running and accessible."
  exit 1
fi

# Let all scripts be executable
chmod +x ./scripts/*

# Run sync-storage.sh job
if ps aux | grep "[s]ync-storage.sh" > /dev/null; then
    echo "Storage synchronization script is already running. Skipping..."
else
    echo "Starting storage synchronization script in the background..."
    nohup ./scripts/sync-storage.sh > ./scripts/sync-storage.log 2>&1 &
    disown
    echo "Storage synchronization script started and disowned."
fi

# Run update-phpini.sh job
bash ./scripts/update-phpini.sh

echo "Setup script completed."
