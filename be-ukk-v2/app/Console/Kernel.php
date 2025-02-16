<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\CalculateLateRentalFines;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan setiap jam
        // $schedule->job(new CalculateLateRentalFines)->hourly();
        
        // Atau jalankan setiap menit untuk testing
        $schedule->job(new CalculateLateRentalFines())
            ->everyMinute()
            ->withoutOverlapping()
            ->onQueue('default')
            ->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    // Tambahkan timezone
    protected function scheduleTimezone(): string
    {
        return 'Asia/Jakarta';
    }

    // Register commands
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}