<?php

namespace App\Filament\Widgets;

use App\Enums\RentalStatus;
use App\Filament\Resources\TrxRentItemResource;
use App\Models\TrxRentItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'Penyewaan Terbaru';
    
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(TrxRentItemResource::getEloquentQuery())
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Penyewaan')
            ->emptyStateDescription('Saat ini belum ada transaksi penyewaan yang tercatat.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->columns([
                Tables\Columns\TextColumn::make('trx_code')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_start_date')
                    ->label('Mulai Sewa')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_end_date')
                    ->label('Selesai Sewa')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn (TrxRentItem $record): string => TrxRentItemResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}