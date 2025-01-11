echo "Installing Composer dependencies..."
composer install

# Laravel Commands
echo "Running Laravel migrations..."
yes | php artisan migrate:refresh

echo "Creating a personal access client for Laravel Passport..."
yes | php artisan passport:client --personal

echo "Seeding the database..."
php artisan db:seed

# Set the duration to run the script (in seconds). 180 minutes = 10800 seconds.
duration=$((1 * 60))

# Get the current time (start time in seconds)
start_time=$(date +%s)

echo "Starting to execute the command. It will run for $duration seconds (180 minutes)."

# Loop to run the command until the duration exceeds 180 minutes
while true
do
    # Calculate the elapsed time
    elapsed_time=$(( $(date +%s) - $start_time ))

    # Check if elapsed time is greater than or equal to the set duration
    if [ $elapsed_time -ge $duration ]; then
        echo "Time limit reached. Stopping the execution."
        break
    fi

    # Run the command and sleep for 5 seconds
    echo "Executing command, elapsed time: $elapsed_time seconds..."
    php artisan sch:work
    # sleep 5
done

echo "Setup completed successfully!"
