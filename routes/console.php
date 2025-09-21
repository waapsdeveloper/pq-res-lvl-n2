<?php

// use App\Console\Commands\CreateRandomOrderJob;
use App\CreateRandomOrderJobClass;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\CreateOrderCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::command(CreateOrderCommand::class)->everySecond();


// Schedule::call(function () {
//     logger()->info('This is a scheduled task');
// })->everySecond();

// Schedule::call(new CreateRandomOrderJobClass)->everyFiveSeconds();

// Schedule::command(CreateRandomOrderJob::class)->everyTenSeconds();
