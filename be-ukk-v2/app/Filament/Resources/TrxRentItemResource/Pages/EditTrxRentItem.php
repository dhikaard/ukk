<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Filament\Resources\TrxRentItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditTrxRentItem extends EditRecord
{
    protected static string $resource = TrxRentItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $previousStatus = $this->record->status;
        $newStatus = $data['status'];

        // Validate status transitions
        if ($previousStatus) {
            if ($newStatus === 'S' && $previousStatus === 'D') {
                if (empty($data['return_date'])) {
                    Notification::make()
                        ->title('Error')
                        ->body('Tanggal pengembalian wajib diisi sebelum mengubah status menjadi Selesai')
                        ->danger()
                        ->send();
                    $data['status'] = 'D';
                    $this->halt(); // Tambahkan ini untuk menghentikan proses penyimpanan
                    return $data;
                }
            }

            if ($previousStatus === 'D' && in_array($newStatus, ['P', 'B', 'T'])) {
                Notification::make()
                    ->title('Error')
                    ->body('Status Sedang Disewa hanya dapat diubah menjadi Selesai')
                    ->danger()
                    ->send();
                $data['status'] = 'D';
                $this->halt();
                return $data;
            }

            if ($previousStatus === 'S') {
                Notification::make()
                    ->title('Error')
                    ->body('Transaksi yang sudah selesai tidak dapat diubah')
                    ->danger()
                    ->send();
                $data['status'] = 'S';
                $this->halt();
                return $data;
            }

            if (in_array($previousStatus, ['B', 'T']) && $newStatus === 'S') {
                Notification::make()
                    ->title('Error')
                    ->body('Transaksi yang dibatalkan/ditolak tidak dapat diselesaikan')
                    ->danger()
                    ->send();
                $data['status'] = $previousStatus;
                $this->halt();
                return $data;
            }

            if ($previousStatus === 'P' && $newStatus === 'S') {
                Notification::make()
                    ->title('Error')
                    ->body('Transaksi harus dalam status Sedang Disewa sebelum dapat diselesaikan')
                    ->danger()
                    ->send();
                $data['status'] = 'P';
                $this->halt();
                return $data;
            }

            // Handle stock management
            if ($newStatus === 'D' && $previousStatus === 'P') {
                DB::beginTransaction();
                try {
                    foreach ($this->record->details as $detail) {
                        if ($detail->item_stock_id === -99) {
                            $detail->item->decrement('stock', $detail->qty);
                            
                            // Check if regular stock is 0
                            if ($detail->item->stock <= 0) {
                                $detail->item->update(['active' => false]);
                            }
                        } else {
                            $itemStock = $detail->itemStock;
                            if ($itemStock) {
                                $itemStock->decrement('stock', $detail->qty);
                                
                                // Check if all size stocks are 0
                                $totalSizeStock = $detail->item->itemStock()->sum('stock');
                                if ($totalSizeStock <= 0 && $detail->item->stock <= 0) {
                                    $detail->item->update(['active' => false]);
                                }
                            }
                        }
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Notification::make()
                        ->title('Error')
                        ->body('Gagal mengupdate stok: ' . $e->getMessage())
                        ->danger()
                        ->send();
                    $this->halt();
                }
            }
            
            if ($newStatus === 'S' && $previousStatus === 'D') {
                DB::beginTransaction();
                try {
                    foreach ($this->record->details as $detail) {
                        if ($detail->item_stock_id === -99) {
                            $detail->item->increment('stock', $detail->qty);
                            
                            // Check if item should be reactivated
                            if (!$detail->item->active && $detail->item->stock > 0) {
                                $detail->item->update(['active' => true]);
                            }
                        } else {
                            $itemStock = $detail->itemStock;
                            if ($itemStock) {
                                $itemStock->increment('stock', $detail->qty);
                                
                                // Check combined stock for size-specific items
                                $totalSizeStock = $detail->item->itemStock()->sum('stock');
                                if (!$detail->item->active && ($totalSizeStock > 0 || $detail->item->stock > 0)) {
                                    $detail->item->update(['active' => true]);
                                }
                            }
                        }
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Notification::make()
                        ->title('Error')
                        ->body('Gagal mengupdate stok: ' . $e->getMessage())
                        ->danger()
                        ->send();
                    $this->halt();
                }
            }
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

}