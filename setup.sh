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
echo "You have 15 seconds to Enter the number of random orders to create (default is 550):"
read -t 15 COUNT

# If no input, default to COUNT
COUNT=${COUNT:-550}

# Start the `php artisan run:random-orders` process in the background
echo "Starting php artisan run:random-orders with count: $COUNT"
php artisan run:random-orders $COUNT

# Wait for 3 seconds (for timeout check)
sleep 3

# After 3 seconds, stop the process
echo "$COUNT orders are created and Setup completed successfully!"
