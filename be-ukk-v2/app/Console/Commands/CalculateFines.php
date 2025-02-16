<?php

namespace App\Console\Commands;

use App\Jobs\CalculateLateRentalFines;
use Illuminate\Console\Command;

class CalculateFines extends Command
{
    protected $signature = 'app:calculate-fines';
    protected $description = 'Calculate fines for late rentals';

    public function handle()
    {
        CalculateLateRentalFines::dispatch();
        $this->info('Fine calculation job dispatched successfully');
    }
}