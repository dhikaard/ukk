<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrxRentItemResource\Pages;
use App\Models\GlobalFine;
use App\Models\Items;
use App\Models\TrxRentItem;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class TrxRentItemResource extends Resource
{
    protected static ?string $model = TrxRentItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penyewa')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Penyewa')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->options(
                                        User::where('active', true)->where('role_id', '!=', 1)->pluck('name', 'id')
                                    ),
                            ]),
                    ]),
                Forms\Components\Section::make('Detail Penyewaan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\Section::make('Inputan')
                                        ->schema([
                                            Forms\Components\Select::make('items_id')
                                                ->label('Barang')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->options(
                                                    Items::where('active', true)->pluck('items_name', 'items_id')
                                                )
                                                ->reactive()
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set)),
                                            Forms\Components\DateTimePicker::make('rent_start_date')
                                                ->label('Tanggal Mulai Sewa')
                                                ->native(false)
                                                ->weekStartsOnMonday()
                                                ->closeOnDateSelection()
                                                ->displayFormat('d F Y, H:i:s')
                                                ->format('Y-m-d H:i:s')
                                                ->required()
                                                ->reactive()
                                                ->minDate(fn ($get, $livewire) => $livewire instanceof Pages\CreateTrxRentItem ? Carbon::today()->toDateString() : $get('rent_start_date'))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateDuration($get, $set)),
                                            Forms\Components\DateTimePicker::make('rent_end_date')
                                                ->label('Tanggal Selesai Sewa')
                                                ->native(false)
                                                ->weekStartsOnMonday()
                                                ->closeOnDateSelection()
                                                ->displayFormat('d F Y, H:i:s')
                                                ->format('Y-m-d H:i:s')
                                                ->required()
                                                ->reactive()
                                                ->minDate(fn ($get, $livewire) => $livewire instanceof Pages\CreateTrxRentItem ? Carbon::today()->toDateString() : $get('rent_start_date'))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateDuration($get, $set))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set)),
                                            Forms\Components\TextInput::make('qty')
                                                ->label('Jumlah Barang')
                                                ->required()
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set)),
                                            Forms\Components\Select::make('global_fine_id')
                                                ->label('Jenis Denda')
                                                ->preload()
                                                ->searchable()
                                                ->reactive()
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set))
                                                ->options(GlobalFine::pluck('fine_name', 'global_fine_id')),
                                        ]),
                                ]),
                                Forms\Components\Group::make([
                                    Forms\Components\Section::make('Hasil Perhitungan')
                                        ->schema([
                                            Forms\Components\TextInput::make('duration')
                                                ->label('Durasi (hari)')
                                                ->required()
                                                ->disabled()
                                                ->reactive()
                                                ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set)),
                                            Forms\Components\TextInput::make('sub_total')
                                                ->label('Total Harga')
                                                ->required()
                                                ->numeric()
                                                ->disabled()
                                                ->reactive()
                                                ->prefix('IDR ')
                                                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                                            Forms\Components\TextInput::make('fine_amount')
                                                ->label('Jumlah Denda')
                                                ->numeric()
                                                ->required()
                                                ->reactive()
                                                ->disabled()
                                                ->visible(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem) // Hanya tampil saat edit
                                                ->prefix('IDR ')
                                                ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                                        ]),
                                ]),
                            ]),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem),
                Forms\Components\Section::make('Detail Penyewaan')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Barang yang Disewa')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Group::make([
                                            Forms\Components\Section::make('Inputan')
                                                ->schema([
                                                    Forms\Components\Select::make('items_id')
                                                        ->label('Barang')
                                                        ->required()
                                                        ->searchable()
                                                        ->preload()
                                                        ->options(
                                                            Items::where('active', true)->pluck('items_name', 'items_id')
                                                        )
                                                        ->reactive()
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set)),
                                                    Forms\Components\DateTimePicker::make('rent_start_date')
                                                        ->label('Tanggal Mulai Sewa')
                                                        ->native(false)
                                                        ->weekStartsOnMonday()
                                                        ->closeOnDateSelection()
                                                        ->displayFormat('d F Y, H:i:s')
                                                        ->format('Y-m-d H:i:s')
                                                        ->required()
                                                        ->reactive()
                                                        ->minDate(fn ($get, $livewire) => $livewire instanceof Pages\CreateTrxRentItem ? Carbon::today()->toDateString() : $get('rent_start_date'))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateDuration($get, $set)),
                                                    Forms\Components\DateTimePicker::make('rent_end_date')
                                                        ->label('Tanggal Selesai Sewa')
                                                        ->native(false)
                                                        ->weekStartsOnMonday()
                                                        ->closeOnDateSelection()
                                                        ->displayFormat('d F Y, H:i:s')
                                                        ->format('Y-m-d H:i:s')
                                                        ->required()
                                                        ->reactive()
                                                        ->minDate(fn ($get, $livewire) => $livewire instanceof Pages\CreateTrxRentItem ? Carbon::today()->toDateString() : $get('rent_start_date'))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateDuration($get, $set))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set)),
                                                    Forms\Components\TextInput::make('qty')
                                                        ->label('Jumlah Barang')
                                                        ->required()
                                                        ->numeric()
                                                        ->reactive()
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set))
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set)),
                                                    Forms\Components\Select::make('global_fine_id')
                                                        ->label('Jenis Denda')
                                                        ->preload()
                                                        ->searchable()
                                                        ->reactive()
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateFineAmount($get, $set))
                                                        ->options(GlobalFine::pluck('fine_name', 'global_fine_id')),
                                                ]),
                                        ]),
                                        Forms\Components\Group::make([
                                            Forms\Components\Section::make('Hasil Perhitungan')
                                                ->schema([
                                                    Forms\Components\TextInput::make('duration')
                                                        ->label('Durasi (hari)')
                                                        ->required()
                                                        ->disabled()
                                                        ->reactive()
                                                        ->afterStateUpdated(fn (callable $get, callable $set) => self::calculateSubTotal($get, $set)),
                                                    Forms\Components\TextInput::make('sub_total')
                                                        ->label('Total Harga')
                                                        ->required()
                                                        ->numeric()
                                                        ->disabled()
                                                        ->reactive()
                                                        ->prefix('IDR ')
                                                        ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                                                    Forms\Components\TextInput::make('fine_amount')
                                                        ->label('Jumlah Denda')
                                                        ->numeric()
                                                        ->required()
                                                        ->reactive()
                                                        ->disabled()
                                                        ->visible(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem) // Hanya tampil saat edit
                                                        ->prefix('IDR ')
                                                        ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                                                ]),
                                        ]),
                                    ]),
                            ])
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateTrxRentItem),
                Forms\Components\Section::make('Status dan Deskripsi')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status Barang')
                                    ->options([
                                        'Pending' => [
                                            'P' => 'Pending',
                                        ],
                                        'In Process' => [
                                            'D' => 'Sedang Disewa',
                                        ],
                                        'Completed' => [
                                            'S' => 'Selesai',
                                        ],
                                        'Cancelled' => [
                                            'B' => 'Dibatalkan',
                                            'T' => 'Ditolak',
                                        ],
                                    ])
                                    ->required()
                                    ->default('P')
                                    ->native(false),
                                Forms\Components\DatePicker::make('return_date')
                                    ->label('Tanggal Pengembalian')
                                    ->native(false)
                                    ->weekStartsOnMonday()
                                    ->closeOnDateSelection()
                                    ->displayFormat('d F Y, H:i:s')
                                    ->minDate(fn ($get) => $get('rent_end_date'))
                                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem),
                                Forms\Components\Textarea::make('desc')
                                    ->label('Deskripsi')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    private static function calculateDuration(callable $get, callable $set)
    {
        $startDate = $get('rent_start_date');
        $endDate = $get('rent_end_date');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->startOfDay();

            if ($end->lessThan($start)) {
                $set('duration', 'End date cannot be earlier than start date');
            } else {
                $duration = $start->diffInDays($end) + 1; // Tambahkan 1 hari untuk menyertakan hari mulai
                $set('duration', $duration);
            }
        } else {
            $set('duration', null);
        }
    }

    private static function calculateSubTotal(callable $get, callable $set)
    {
        $itemId = $get('items_id');
        $qty = $get('qty');
        $duration = $get('duration');

        if ($itemId && $qty && $duration) {
            $item = Items::find($itemId);
            if ($item) {
                $subTotal = $item->price * $qty * $duration;
                $set('sub_total', $subTotal);
            } else {
                $set('sub_total', 0);
            }
        } else {
            $set('sub_total', 0);
        }
    }

    private static function calculateFineAmount(callable $get, callable $set)
    {
        $rentEndDate = $get('rent_end_date');
        $globalFineId = $get('global_fine_id');
        $qty = $get('qty');
        $itemId = $get('items_id');

        if ($rentEndDate && $globalFineId && $qty && $itemId) {
            $rentEndDate = Carbon::parse($rentEndDate)->endOfDay();
            $globalFine = GlobalFine::find($globalFineId);
            $item = Items::find($itemId);
            $now = Carbon::now();

            if ($globalFine && $item) {
                $timeLimit = Carbon::parse($rentEndDate)->setTimeFrom(Carbon::parse($globalFine->time_limit));
                $finePercentage = $globalFine->fine_percentage;
                $fineAmount = 0;

                // Jika waktu sekarang lebih besar dari rent_end_date
                if ($now->greaterThan($rentEndDate)) {
                    // Jika melewati time_limit, denda menjadi 100%
                    if ($now->greaterThan($timeLimit)) {
                        $fineAmount = $item->price * $qty;
                    } else {
                        // Jika masih dalam batas time_limit, gunakan fine_percentage
                        $fineAmount = ($finePercentage / 100) * $item->price * $qty;
                    }
                }

                $set('fine_amount', min($fineAmount, $item->price * $qty));
            } else {
                $set('fine_amount', 0);
            }
        } else {
            $set('fine_amount', 0);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Penyewa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('item.items_name')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rent_start_date')
                    ->label('Tanggal Mulai Sewa')
                    ->dateTime('d F Y, H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_end_date')
                    ->label('Tanggal Selesai Sewa')
                    ->dateTime('d F Y, H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi (hari)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Jumlah')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_total')
                    ->label('Total Harga')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('fine_amount')
                    ->label('Jumlah Denda')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $statusLabels = [
                            'P' => 'Pending',
                            'D' => 'Sedang Disewa',
                            'S' => 'Selesai',
                            'B' => 'Dibatalkan',
                            'T' => 'Ditolak',
                        ];
                        return $statusLabels[$record->status] ?? 'Unknown';
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrxRentItems::route('/'),
            'create' => Pages\CreateTrxRentItem::route('/create'),
            'edit' => Pages\EditTrxRentItem::route('/{record}/edit'),
        ];
    }
}