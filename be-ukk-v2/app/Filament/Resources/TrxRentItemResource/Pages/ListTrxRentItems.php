<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Filament\Resources\TrxRentItemResource;
use App\Filament\Widgets\RentStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListTrxRentItems extends ListRecords
{
    protected static string $resource = TrxRentItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RentStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'P')),
            'active' => Tab::make('Sedang Disewa')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'D')),
            'completed' => Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'S')),
            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'B')),
            'rejected' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'T')),
        ];
    }
}