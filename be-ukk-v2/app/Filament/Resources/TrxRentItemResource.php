<?php

namespace App\Filament\Resources;

use App\Enums\RentalStatus;
use App\Filament\NavigationGroups;
use App\Filament\Resources\TrxRentItemResource\Pages;
use App\Filament\Widgets\RentStats;
use App\Models\Items;
use App\Models\TrxRentItem;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrxRentItemResource extends Resource
{
    protected static ?string $model = TrxRentItem::class;

    protected static ?string $modelLabel = 'Penyewaan';
    protected static ?string $pluralModelLabel = 'Data Penyewaan';
    protected static ?string $navigationLabel = 'Penyewaan';
    protected static ?string $navigationGroup = NavigationGroups::RENT_MANAGEMENT;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $recordTitleAttribute = 'trx_code';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Customer Information Section
            Forms\Components\Section::make('Informasi Penyewa')
                ->description('Masukkan informasi penyewa dan periode sewa')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('trx_code')
                            ->label('Kode Transaksi')
                            ->disabled()
                            ->dehydrated(true) // Add this to ensure value persists
                            ->default(function ($record) {
                                if ($record) {
                                    return $record->trx_code;
                                }
                                return null;
                            }),
                            Forms\Components\Select::make('user_id')
                                ->label('Penyewa')
                                ->disabled(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem)
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options(function ($record) {
                                    $query = User::where('active', true);
                                    if (!$record) {
                                        $query->where('role_id', '!=', 1);
                                    }
                                    return $query->pluck('name', 'id');
                                })
                        ]),
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DateTimePicker::make('rent_start_date')
                                ->disabled(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem)
                                ->label('Tanggal Mulai Sewa')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->reactive()
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateDuration($get, $set)),
                            Forms\Components\DateTimePicker::make('rent_end_date')
                                ->disabled(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem)
                                ->label('Tanggal Selesai Sewa')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->reactive()
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateDuration($get, $set)),
                        ]),
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('duration')
                                ->label('Durasi (hari)')
                                ->disabled()
                                ->reactive(),
                            Forms\Components\TextInput::make('total')
                                ->label('Total Harga')
                                ->disabled()
                                ->dehydrated(true)  // Add this to ensure value persists
                                ->reactive()        // Make reactive
                                ->prefix('Rp'),
                            Forms\Components\TextInput::make('total_fine_amount')
                                ->label('Total Denda')
                                ->disabled()
                                ->prefix('Rp'),
                        ]),
                ])
                ->columns(2)
                ->disabled(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem),

            // Rental Details Section
            Forms\Components\Section::make('Detail Barang')
                ->description('Pilih barang yang akan disewa dan jumlahnya')
                ->schema([
                    Forms\Components\Repeater::make('details')
                        ->relationship('details')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('items_id')
                                        ->label('Barang')
                                        ->required()
                                        ->searchable()
                                        ->reactive()
                                        ->options(fn () => Items::where('active', true)->get()
                                            ->mapWithKeys(fn ($item) => [
                                                $item->items_id => "{$item->items_name} (Rp {$item->price})"
                                            ]))
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            if ($state) {
                                                $item = Items::find($state);
                                                if ($item) {
                                                    // Reset size when item changes
                                                    $set('size', null);

                                                    // Generate trx_code if needed
                                                    if (empty($get('../../trx_code'))) {
                                                        $itemPrefix = explode('-', $item->items_code)[0];
                                                        $latestTrx = TrxRentItem::latest('trx_rent_items_id')->first();
                                                        $id = $latestTrx ? $latestTrx->trx_rent_items_id + 1 : 1;
                                                        $trxCode = "TRX-{$itemPrefix}-" . date('dmy') . "-{$id}";
                                                        $set('../../trx_code', $trxCode);
                                                    }
                                                    // Set initial qty and calculate subtotal
                                                    $qty = $get('qty') ?? 1;
                                                    self::calculateSubTotal($get, $set, $state, $qty);
                                                }
                                            }
                                        }),
                                        Forms\Components\TextInput::make('sub_total')
                                        ->label('Sub Total')
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->reactive(),
                                        Forms\Components\Select::make('item_stock_id')
                                        ->label('Ukuran')
                                        ->options(function (Get $get) {
                                            $itemId = $get('items_id');
                                            if (!$itemId) return [];

                                            $item = Items::find($itemId);
                                            if (!$item) return [];

                                            // Get all selected item_stock_ids from other repeater items
                                            $allDetails = collect($get('../../details'));
                                            $selectedStockIds = $allDetails
                                                ->where('items_id', $itemId)
                                                ->pluck('item_stock_id')
                                                ->filter()
                                                ->toArray();

                                            // Current item stock id (for edit)
                                            $currentStockId = $get('item_stock_id');
                                            if ($currentStockId) {
                                                $selectedStockIds = array_diff($selectedStockIds, [$currentStockId]);
                                            }

                                            $options = collect();

                                            // Add Regular option if not already selected
                                            if (!in_array(-99, $selectedStockIds)) {
                                                $options->put(-99, "Regular (Stok: {$item->stock})");
                                            }

                                            // Add available size options
                                            $itemStocks = $item->itemStock()->get();
                                            foreach ($itemStocks as $stock) {
                                                if (!in_array($stock->item_stock_id, $selectedStockIds)) {
                                                    $options->put(
                                                        $stock->item_stock_id,
                                                        "Size {$stock->size} (Stok: {$stock->stock})"
                                                    );
                                                }
                                            }

                                            return $options;
                                        })
                                        ->reactive()
                                        ->searchable()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            // Reset quantity
                                            $set('qty', 1);

                                            // Recalculate subtotal
                                            $itemId = $get('items_id');
                                            if ($itemId) {
                                                self::calculateSubTotal($get, $set, $itemId, 1);
                                            }
                                        }),
                                        Forms\Components\TextInput::make('fine_amount')
                                        ->label('Denda')
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->prefix('Rp')
                                        ->default(0)
                                        ->numeric(),
                                        Forms\Components\TextInput::make('qty')
                                        ->label('Jumlah')
                                        ->required()
                                        ->numeric()
                                        ->default(1)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $itemId = $get('items_id');
                                            $itemStockId = $get('item_stock_id');
                                            $qty = $state ?? 1;

                                            if ($itemId) {
                                                $item = Items::find($itemId);

                                                // Check stock based on item_stock_id
                                                if ($itemStockId == -99) {
                                                    $stock = $item->stock;
                                                } else {
                                                    $stock = $item->itemStock()
                                                        ->where('item_stock_id', $itemStockId)
                                                        ->value('stock') ?? 0;
                                                }

                                                if ($qty > $stock) {
                                                    Notification::make()
                                                        ->title('Error')
                                                        ->body("Stok tidak mencukupi. Tersedia: {$stock}")
                                                        ->danger()
                                                        ->send();

                                                    $set('qty', 1); // Reset to default
                                                    return;
                                                }

                                                self::calculateSubTotal($get, $set, $itemId, $qty);
                                            }
                                        })
                                ])
                        ])
                        ->required()
                        ->minItems(1)
                        ->defaultItems(1)
                        ->reorderable()
                        ->live()  // Important - make repeater live
                        ->afterStateUpdated(function (Set $set, $state) {
                            // Calculate total from all sub_totals
                            $total = collect($state)->sum('sub_total');
                            Log::info('Calculating total:', ['state' => $state, 'total' => $total]);
                            $set('../../total', $total);  // Set parent total
                        }),
                ])
                ->columns(['sm' => 1, 'lg' => 1])
                ->disabled(fn ($livewire) => $livewire instanceof Pages\EditTrxRentItem),

            // Status & Description Section
            Forms\Components\Section::make('Status & Keterangan')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\ToggleButtons::make('status')
                                ->inline()
                                ->options(RentalStatus::class)
                                ->enum(RentalStatus::class)
                                ->required()
                                ->disabled(fn ($livewire) => $livewire instanceof Pages\CreateTrxRentItem)
                                ->default('P')
                                ->reactive(),

                            Forms\Components\DateTimePicker::make('return_date')
                                ->label('Tanggal Pengembalian')
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->visible(fn (Get $get) => in_array($get('status'), ['D', 'S']))
                                ->required(fn (Get $get) => $get('status') === 'D'),
                        ]),

                    Forms\Components\Textarea::make('desc')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull()
                ])->columnSpanFull(),
        ])
        ->disabled(fn ($record) => $record && in_array($record->status, ['S', 'B', 'T']));
    }

    private static function calculateDuration(Get $get, Set $set): void
    {
        $startDate = $get('rent_start_date');
        $endDate = $get('rent_end_date');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            if ($end->lessThan($start)) {
                Notification::make()
                ->title('Error')
                ->body("Tanggal selesai tidak boleh lebih awal dari tanggal mulai")
                ->danger()
                ->send();
                $set('duration', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
            } else {
                $duration = $start->diffInDays($end) + 1;
                $set('duration', $duration);
                self::calculateTotals($get, $set);
            }
        }
    }

    private static function calculateTotals(Get $get, Set $set): void
    {
        $details = collect($get('details'));
        $duration = $get('duration') ?? 0;

        // Calculate subtotals
        $total = $details->reduce(function ($sum, $detail) use ($duration) {
            if (empty($detail['items_id']) || empty($detail['qty'])) {
                return $sum;
            }

            $item = Items::find($detail['items_id']);
            return $sum + ($item->price * $detail['qty'] * $duration);
        }, 0);

        $set('total', $total);

        // Calculate fines if applicable
        $rentEndDate = $get('rent_end_date');
        if ($rentEndDate) {
            $now = Carbon::now();
            $endDate = Carbon::parse($rentEndDate);

            if ($now->greaterThan($endDate)) {
                $fineTotal = $details->reduce(function ($sum, $detail) {
                    if (empty($detail['items_id']) || empty($detail['qty'])) {
                        return $sum;
                    }

                    $item = Items::find($detail['items_id']);
                    $globalFine = $item->globalFine;

                    if ($globalFine) {
                        return $sum + ($item->price * $detail['qty'] * ($globalFine->fine_percentage / 100));
                    }

                    return $sum;
                }, 0);

                $set('total_fine_amount', $fineTotal);
            }
        }
    }

    private static function calculateSubTotal(Get $get, Set $set, $itemId, $qty): void
    {
        if ($itemId) {
            $item = Items::find($itemId);
            if ($item) {
                $duration = $get('../../duration') ?? 1;
                $subTotal = $item->price * $qty * $duration;
                $set('sub_total', $subTotal);

                // Update main total
                $details = collect($get('../../details'));
                $total = $details->sum('sub_total');
                $set('../../total', $total);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trx_code')
                    ->label('No. Transaksi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor transaksi disalin')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Pembayaran')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total'),
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Sewa')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(RentalStatus::class)
                    ->multiple(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->placeholder('Pilih tanggal awal')
                            ->native(false),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->placeholder('Pilih tanggal akhir')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Dari ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Sampai ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Tanggal Sewa')
                    ->date()
                    ->collapsible(),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getWidgets(): array
    {
        return [
            RentStats::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'trx_code',
            'user.name',
            'status',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->trx_code;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Penyewa' => $record->user->name,
            'Status' => $record->status->getLabel(),
            'Total' => 'Rp ' . number_format($record->total, 0, ',', '.'),
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