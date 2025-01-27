<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Filament\Resources\TrxRentItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrxRentItem extends EditRecord
{
    protected static string $resource = TrxRentItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
