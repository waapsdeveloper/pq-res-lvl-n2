#!/bin/bash

# Installing Composer dependencies
echo "Installing Composer dependencies..."
# composer install

# Laravel Commands
echo "Running Laravel migrations..."
php artisan migrate:fresh

echo "Creating a personal access client for Laravel Passport..."
yes | php artisan passport:client --personal 

echo "Seeding the database..."
yes | php artisan db:seed

# Set the duration to run the script (in seconds). 2 hours = 7200 seconds
duration=$((1.5 * 60))  # 2 hours in seconds

# Get the current time (start time in seconds)
start_time=$(date +%s)

echo "Starting to execute the command. It will run for $duration seconds (2 hours)."

# Start the PHP artisan sch:work process in the background
php artisan sch:work &  # Run the command in the background
work_pid=$!  # Get the PID (Process ID) of the background process

echo "php artisan sch:work started. Process ID: $work_pid"

# Wait for 2 hours (7200 seconds)
while true
do
    # Calculate the elapsed time
    elapsed_time=$(( $(date +%s) - $start_time ))

    # Check if elapsed time is greater than or equal to the set duration (2 hours)
    if [ $elapsed_time -ge $duration ]; then
        echo "Time limit reached. Sending Ctrl+C (SIGINT) signal 3 times to stop the process."

        # Send SIGINT (Ctrl+C) to the background process 3 times
        for i in {1..3}
        do
            kill -SIGINT $work_pid  # Sending Ctrl+C (SIGINT) signal
            echo "Sent Ctrl+C (SIGINT) signal #$i"
            sleep 1  # Wait for a second between each signal
        done

        # Exit the loop after sending 3 Ctrl+C signals
        break
    fi

    sleep 5  # Sleep to avoid high CPU usage while checking the elapsed time
done

echo "Setup completed successfully!"
