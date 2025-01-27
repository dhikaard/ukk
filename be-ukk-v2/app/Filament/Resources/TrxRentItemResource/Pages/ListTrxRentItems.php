<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Filament\Resources\TrxRentItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrxRentItems extends ListRecords
{
    protected static string $resource = TrxRentItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
