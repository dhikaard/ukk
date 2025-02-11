<?php

namespace App\Filament\Widgets;

use App\Models\TrxRentItem;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RentRevenueCharts extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan per Bulan';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = [];
        
        // Get revenue data for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            $data[] = TrxRentItem::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('status', ['S', 'D']) // Only count completed and active rentals
                ->sum('total');
        }

        $labels = collect(range(11, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('M');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)', // Green with opacity
                    'borderColor' => 'rgb(34, 197, 94)', // Solid green
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                    ],
                    'ticks' => [
                        'callback' => 'function(value) {
                            return "Rp " + new Intl.NumberFormat("id-ID").format(value);
                        }',
                    ],
                ],
            ],
        ];
    }
}