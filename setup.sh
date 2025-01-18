#!/bin/bash

# Installing Composer dependencies
echo "Installing Composer dependencies..."
# composer install

# Laravel Commands
echo "Running Laravel migrations..."
php artisan migrate:fresh <<EOF
local
EOF

echo "Creating a personal access client for Laravel Passport..."
php artisan passport:client --personal <<EOF
local
EOF

echo "Seeding the database..."
php artisan db:seed <<EOF
local
EOF

# Set the duration to run the script (in seconds). 2 hours = 7200 seconds
total_duration=$((10 * 60))  # Example: 10 minutes total

# Duration to run the command (in seconds)
run_duration=120  # 2 minutes

# Wait time before restarting the command (in seconds)
wait_duration=15  # 15 seconds

# Start time of the script
start_time=$(date +%s)

while true; do
    # Calculate total elapsed time
    elapsed_time=$(( $(date +%s) - $start_time ))

    # Exit the loop if the total duration is reached
    if [ $elapsed_time -ge $total_duration ]; then
        echo "Total duration of $total_duration seconds reached. Stopping the loop."
        break
    fi

    # Start the `php artisan sch:work` command in the background
    echo "Starting php artisan sch:work..."
    php artisan sch:work &
    work_pid=$!  # Get the PID of the background process

    # Let the process run for the defined duration
    sleep $run_duration

    # Stop the process after the run duration
    echo "Stopping php artisan sch:work after $run_duration seconds..."
    kill -SIGINT $work_pid  # Gracefully stop the process
    sleep 1  # Give it time to terminate
    kill -9 $work_pid 2>/dev/null  # Force kill if it's still running

    # Wait before restarting
    echo "Waiting for $wait_duration seconds before restarting..."
    sleep $wait_duration
done

echo "Script completed successfully!"