#!/bin/bash

# Installing Composer dependencies
echo "Installing Composer dependencies..."
# composer install

# Running Laravel migrations
echo "Running Laravel migrations..."
php artisan migrate:fresh

# Creating a personal access client for Laravel Passport
echo "Creating a personal access client for Laravel Passport..."
php artisan passport:client --personal <<EOF
local
EOF

# Seeding the database
echo "Seeding the database..."
php artisan db:seed

# Ask for custom count with 15 seconds timeout
echo "you have 10 seconds to Enter the number of random orders to create (default is 250):"
read -t 3 COUNT

# If no input, default to 200
COUNT=${COUNT:-250}

# Start the `php artisan run:random-orders` process in the background
echo "Starting php artisan run:random-orders with count: $COUNT"
php artisan run:random-orders $COUNT

# Wait for 15 seconds (for timeout check)
sleep 3

# After 15 seconds, stop the process
echo "Stopping php artisan run:random-orders after 15 seconds."
echo "Setup completed successfully!"
