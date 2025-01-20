#!/bin/bash

# Installing Composer dependencies
# echo "Installing Composer dependencies..."
# composer install

# Running Laravel migrations
echo "Running Laravel migrations..."
# php artisan migrate:fresh

# Creating a personal access client for Laravel Passport
echo "Creating a personal access client for Laravel Passport..."
# php artisan passport:client --personal <<EOF
# local
# EOF

# Seeding the database
echo "Seeding the database..."
# php artisan db:seed

# Start the `php artisan sch:work` process in the background
echo "Starting php artisan sch:work..."
php artisan sch:work &
work_pid=$!  # Capture the Process ID (PID) of the background process

# Wait for 1 minute
sleep 60

# Send the interrupt command
echo "Stopping php artisan sch:work by sending php artisan schedule:interrupt..."
php artisan schedule:interrupt

# Ensure the process has stopped gracefully
kill -SIGINT $work_pid
wait $work_pid 2>/dev/null

echo "php artisan sch:work has been stopped after 1 minute."
echo "Setup completed successfully!"
