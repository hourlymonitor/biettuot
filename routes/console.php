<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('biettuot:status', function () {
    $this->info('BiếtTuốt MVP is active.');
})->purpose('Check app status');
