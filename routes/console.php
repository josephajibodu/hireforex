<?php

use App\Actions\MoveArbitrageTradesToQueue;
use App\Jobs\MoveReserveBalanceToMainJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();

Schedule::call(function (MoveArbitrageTradesToQueue $tradesToQueue) {
    $tradesToQueue->execute();
})->hourly();

Schedule::job(new MoveReserveBalanceToMainJob())->hourly();

// * * * * * /usr/bin/php -q /home/yourusername/public_html/artisan queue:work --tries=3 --stop-when-empty >> /dev/null 2>&1
// * * * * * /usr/bin/php -q /home/yourusername/public_html/artisan schedule:run >> /dev/null 2>&1