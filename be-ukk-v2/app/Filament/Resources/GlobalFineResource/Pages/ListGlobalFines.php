<?php

namespace App\Filament\Resources\GlobalFineResource\Pages;

use App\Filament\Resources\GlobalFineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGlobalFines extends ListRecords
{
    protected static string $resource = GlobalFineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
