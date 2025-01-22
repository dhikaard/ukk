<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use App\Filament\Resources\ProductsResource;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditProducts extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('Data sebelum diupdate:', $data); // Debugging

        // Menambahkan kondisi untuk memeriksa apakah `stock` produk utama diatur menjadi 0
        // dan jika `productStock` kosong, maka tampilkan pesan error
        if (empty($this->data['stock']) && empty($this->data['productStock'])) {
            Notification::make()
                ->title('Error')
                ->body('Harap isi salah satu antara stok produk atau ukuran & stok.')
                ->danger()
                ->send();

            $this->halt(); // Mencegah penyimpanan data
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
