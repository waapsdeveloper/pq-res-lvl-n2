<?php

namespace App\Console\Commands;

use App\CreateRandomOrderJobClass;
use Illuminate\Console\Command;

class RunRandomOrders extends Command
{
    // Add an optional argument for the number of jobs to run
    protected $signature = 'run:random-orders {count=550}';
    protected $description = 'Run CreateRandomOrderJobClass for a specified number of times';

    public function handle()
    {
        // Get the number of jobs to run (either from the argument or default to 50)
        $count = $this->argument('count');

        // Validate that the count is a positive integer
        if (!is_numeric($count) || $count <= 0) {
            $this->error('Please provide a valid positive number.');
            return;
        }

        // Run the jobs
        for ($i = 0; $i < $count; $i++) {
            // Dispatch the job to the queue
            dispatch(new CreateRandomOrderJobClass());

            // Log to keep track
            $this->info("Random order created: " . ($i + 1));

            // Optional: Add a delay if you need a small break between job executions
            sleep(1);  // 1 second delay between each job execution
        }

        $this->info("$count Random orders created successfully!");
    }
}
