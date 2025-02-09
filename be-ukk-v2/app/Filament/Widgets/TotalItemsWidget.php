<?php

namespace App\Filament\Widgets;

use App\Models\Items;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TotalItemsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Barang', Items::where('active', true)->count())
                ->description('Jumlah total produk yang tersedia')
                ->icon('heroicon-o-cube'),
        ];
    }

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.resources.items.index');
    }

}