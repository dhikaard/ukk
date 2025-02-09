<?php

namespace App\Filament\Resources\GlobalFineResource\Pages;

use App\Filament\Resources\GlobalFineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGlobalFine extends EditRecord
{
    protected static string $resource = GlobalFineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
