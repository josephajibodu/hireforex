<?php

use App\Actions\MoveArbitrageTradesToQueue;
use App\Jobs\MoveReserveBalanceToMainJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::command('orders:complete-delivered')->everyFiveMinutes();

// * * * * * /usr/bin/php -q /home/yourusername/public_html/artisan queue:work --tries=3 --stop-when-empty >> /dev/null 2>&1
// * * * * * /usr/bin/php -q /home/yourusername/public_html/artisan schedule:run >> /dev/null 2>&1