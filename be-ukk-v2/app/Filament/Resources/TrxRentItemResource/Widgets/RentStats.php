<?php

namespace App\Filament\Widgets;

use App\Models\TrxRentItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RentStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        // Get trend data for chart
        $rentData = Trend::model(TrxRentItem::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        // Calculate total rentals directly from DB
        $totalRentals = TrxRentItem::whereYear('created_at', '>=', now()->subYear())
            ->count();

        // Calculate other stats
        $totalRevenue = TrxRentItem::whereIn('status', ['S', 'D'])
            ->sum('total');

        $activeRentals = TrxRentItem::where('status', 'D')
            ->count();

        $averageRental = TrxRentItem::whereIn('status', ['S', 'D'])
            ->avg('total') ?? 0;

        return [
            Stat::make('Total Penyewaan', $totalRentals)
                ->description('Dalam 1 Tahun terakhir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($rentData->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),

            Stat::make('Sedang Disewa', $activeRentals)
                ->description('Penyewaan aktif')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Rata-rata: Rp ' . number_format($averageRental, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}