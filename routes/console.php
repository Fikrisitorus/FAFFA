<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule payment status sync every 5 minutes
// Artisan::command('payment:sync-status', function () {
//     $this->call('payment:sync-status', ['--limit' => 10]);
// })->purpose('Sync payment status from Midtrans')->everyFiveMinutes();
