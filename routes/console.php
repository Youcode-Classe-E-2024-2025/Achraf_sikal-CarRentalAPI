<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // Update rental statuses
    DB::table('rentals')
        ->where('end_date', '<', now())
        ->where('status', '<>', 'completed')
        ->update(['status' => 'completed']);

    // Update car availability
    DB::table('cars')
        ->whereIn('id', function ($query) {
            $query->select('car_id')->from('rentals')->where('status', 'completed');
        })
        ->update(['available' => true]);
})->daily();
