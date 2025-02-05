<?php

namespace App\Filament\Resources\ItemsResource\Pages;

use App\Filament\Resources\ItemsResource;
use App\Models\TrxRentItem;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItems extends EditRecord
{
    protected static string $resource = ItemsResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Check if item is being rented
        $activeRentals = TrxRentItem::whereHas('details', function ($query) {
            $query->where('items_id', $this->record->items_id);
        })->where('status', 'D')->count();

        if ($activeRentals > 0) {
            Notification::make()
                ->title('Error')
                ->body('Barang ini sedang dalam penyewaan. Tidak dapat diedit.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Original stock validation
        if (empty($this->data['stock']) && empty($this->data['itemStock'])) {
            Notification::make()
                ->title('Error')
                ->body('Harap isi salah satu antara stok produk atau ukuran & stok.')
                ->danger()
                ->send();

            $this->halt();
        }

        // ItemStock and Stock validation
        if (!empty($this->data['itemStock']) && $this->data['stock'] != 0) {
            Notification::make()
                ->title('Error')
                ->body('Jika menggunakan ukuran & stok, stok produk harus bernilai 0.')
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    public function beforeEdit(): void
    {
        // Check before form loads
        $activeRentals = TrxRentItem::whereHas('details', function ($query) {
            $query->where('items_id', $this->record->items_id);
        })->where('status', 'D')->count();

        if ($activeRentals > 0) {
            Notification::make()
                ->title('Error')
                ->body('Barang ini sedang dalam penyewaan. Tidak dapat diedit.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    // Check before deletion
                    $activeRentals = TrxRentItem::whereHas('details', function ($query) {
                        $query->where('items_id', $this->record->items_id);
                    })->where('status', 'D')->count();

                    if ($activeRentals > 0) {
                        Notification::make()
                            ->title('Error')
                            ->body('Barang ini sedang dalam penyewaan. Tidak dapat dihapus.')
                            ->danger()
                            ->send();

                        $this->halt();
                    }
                }),
        ];
    }
}