<?php

namespace App\Filament\Widgets;

use App\Models\TrxRentItem;
use App\Models\Items;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            Carbon::now()->startOfMonth();

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            Carbon::now();

        // Calculate revenue
        $revenue = TrxRentItem::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'S')
            ->sum('total');

        // Calculate previous period revenue
        $previousStartDate = clone $startDate;
        $previousEndDate = clone $endDate;
        $previousStartDate->subMonth();
        $previousEndDate->subMonth();

        $previousRevenue = TrxRentItem::query()
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', 'S')
            ->sum('total');

        // Calculate difference
        $difference = $revenue - $previousRevenue;
        $isIncrease = $difference > 0;

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return Number::format($number, 0);
            }
            if ($number < 1000000) {
                return Number::format($number / 1000, 3);
            }
            return Number::format($number / 1000000, 3);
        };

        // Format difference using existing formatNumber function
        $formattedDifference = 'Rp ' . $formatNumber(abs($difference));
        $description = $formattedDifference . ($isIncrease ? ' kenaikan' : ' penurunan');

        // Calculate active rentals
        $activeRentals = TrxRentItem::where('status', 'D')->count();

        // Calculate available items
        $availableItems = Items::where('active', true)
            ->sum('stock');

        // Get chart data for last 7 days
        $lastWeekRevenue = collect(range(6, 0))->map(function ($day) {
            return TrxRentItem::whereDate('created_at', Carbon::now()->subDays($day))
                ->where('status', 'S')
                ->sum('total');
        })->toArray();

        return [
            Stat::make('Pendapatan', 'Rp ' . $formatNumber($revenue))
                ->description($description)
                ->descriptionIcon($isIncrease ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($lastWeekRevenue)
                ->color($isIncrease ? 'success' : 'danger'),

            Stat::make('Penyewaan Aktif', $formatNumber($activeRentals))
                ->description('Sedang Disewa')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Barang Tersedia', $formatNumber($availableItems))
                ->description('Stok Tersedia')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
        ];
    }
}