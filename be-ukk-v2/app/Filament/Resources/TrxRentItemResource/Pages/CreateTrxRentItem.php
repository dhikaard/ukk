<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Filament\Resources\TrxRentItemResource;
use App\Models\TrxRentItem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTrxRentItem extends CreateRecord
{
    protected static string $resource = TrxRentItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $items = $data['items'];
        unset($data['items']);

        foreach ($items as $item) {
            $itemData = array_merge($data, $item);
            TrxRentItem::create($itemData);
        }

        return new TrxRentItem(); // Return a new instance to satisfy the method signature
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}