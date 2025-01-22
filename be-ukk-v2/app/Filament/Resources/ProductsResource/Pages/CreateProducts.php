<?php
namespace App\Filament\Resources\ProductsResource\Pages;

use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CreateProducts extends CreateRecord
{
    protected static string $resource = ProductsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Data sebelum disimpan:', $data);
        Log::info('=== Edit Produk: Data sebelum diupdate ===', $data);

        if (empty($data['stock']) && empty($data['productStock'])) {
            Log::warning('Validasi gagal: Stock & productStock kosong!', $data);
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
