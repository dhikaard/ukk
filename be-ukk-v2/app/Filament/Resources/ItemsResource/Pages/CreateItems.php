<?php
namespace App\Filament\Resources\ItemsResource\Pages;

use App\Filament\Resources\ItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CreateItems extends CreateRecord
{
    protected static string $resource = ItemsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Data sebelum diupdate:', $data); // Debugging
        if (empty($this->data['stock']) && empty($this->data['itemStock'])) {
            Notification::make()
                ->title('Error')
                ->body('Harap isi salah satu antara stok produk atau ukuran & stok.')
                ->danger()
                ->send();

            $this->halt(); // Mencegah penyimpanan data
        }

        return $data;
    }

}
