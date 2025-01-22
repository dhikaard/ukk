<?php

namespace App\Filament\Resources\CategoryProductsResource\Pages;

use App\Filament\Resources\CategoryProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryProducts extends EditRecord
{
    protected static string $resource = CategoryProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

}
