<?php

namespace App\Filament\Widgets;

use App\Models\TrxRentItem;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RentItemCharts extends ChartWidget
{
    protected static ?string $heading = 'Penyewaan per Bulan';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = [];
        
        // Get data for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            $data[] = TrxRentItem::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', '!=', 'B') // Exclude cancelled
                ->where('status', '!=', 'T') // Exclude rejected
                ->count();
        }

        $labels = collect(range(11, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('M');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Penyewaan',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // Blue with opacity
                    'borderColor' => 'rgb(59, 130, 246)', // Solid blue
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
                ],
            ],
        ];
    }
}