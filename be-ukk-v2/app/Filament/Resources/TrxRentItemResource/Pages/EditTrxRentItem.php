<?php

namespace App\Filament\Resources\TrxRentItemResource\Pages;

use App\Enums\RentalStatus;
use App\Filament\Resources\TrxRentItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Convert previous status from Enum to string if needed
        $previousStatus = $this->record->status instanceof RentalStatus 
            ? $this->record->status->value 
            : $this->record->status;
            
        $newStatus = $data['status'];

        Log::info('Status Update Check', [
            'previous' => $previousStatus,
            'new' => $newStatus,
            'record_id' => $this->record->trx_rent_items_id
        ]);

        // Validate status transitions
        if ($previousStatus) {
            // Check D to P/B/T transition (prevent going back from active)
            if ($previousStatus === 'D' && in_array($newStatus, ['P', 'B', 'T'])) {
                Notification::make()
                    ->title('Error')
                    ->body('Status Sedang Disewa hanya dapat diubah menjadi Selesai')
                    ->danger()
                    ->persistent()
                    ->send();
                
                $data['status'] = 'D';
                $this->halt();
                return $data;
            }

            // Check S to any status (prevent changing completed)
            if ($previousStatus === 'S') {
                Notification::make()
                    ->title('Error')
                    ->body('Transaksi yang sudah selesai tidak dapat diubah')
                    ->danger()
                    ->persistent()
                    ->send();
                    
                $data['status'] = 'S';
                $this->halt();
                return $data;
            }

            // Check D to S transition (require return date)
            if ($newStatus === 'S' && $previousStatus === 'D') {
                if (empty($data['return_date'])) {
                    Notification::make()
                        ->title('Error')
                        ->body('Tanggal pengembalian wajib diisi sebelum mengubah status menjadi Selesai')
                        ->danger()
                        ->persistent()
                        ->send();
                        
                    $data['status'] = 'D';
                    $this->halt();
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
                    Log::info('Processing P to D transition', [
                        'details' => $this->record->details->toArray()
                    ]);

                    foreach ($this->record->details as $detail) {
                        // For regular items
                        if ($detail->item_stock_id === -99) {
                            $item = $detail->item;
                            if ($item->stock < $detail->qty) {
                                throw new \Exception("Stok tidak mencukupi untuk {$item->items_name}");
                            }
                            $item->decrement('stock', $detail->qty);
                            
                            Log::info('Regular stock updated', [
                                'item' => $item->items_name,
                                'qty' => $detail->qty,
                                'new_stock' => $item->fresh()->stock
                            ]);

                            // Check if regular stock is 0
                            if ($item->fresh()->stock <= 0) {
                                $item->update(['active' => false]);
                            }
                        } 
                        // For items with specific sizes
                        else {
                            $itemStock = $detail->itemStock;
                            if (!$itemStock) {
                                throw new \Exception("Size stock not found");
                            }

                            if ($itemStock->stock < $detail->qty) {
                                throw new \Exception("Stok ukuran tidak mencukupi untuk {$detail->item->items_name}");
                            }

                            $itemStock->decrement('stock', $detail->qty);
                            
                            Log::info('Size stock updated', [
                                'item' => $detail->item->items_name,
                                'size' => $itemStock->size,
                                'qty' => $detail->qty,
                                'new_stock' => $itemStock->fresh()->stock
                            ]);

                            // Check if all size stocks are 0
                            $totalSizeStock = $detail->item->itemStock()->sum('stock');
                            if ($totalSizeStock <= 0 && $detail->item->stock <= 0) {
                                $detail->item->update(['active' => false]);
                            }
                        }
                    }
                    DB::commit();
                    
                    Notification::make()
                        ->title('Sukses')
                        ->body('Status berhasil diubah dan stok diperbarui')
                        ->success()
                        ->send();

                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error('Stock update failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    Notification::make()
                        ->title('Error')
                        ->body('Gagal mengupdate stok: ' . $e->getMessage())
                        ->danger()
                        ->send();

                    $data['status'] = $previousStatus;
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