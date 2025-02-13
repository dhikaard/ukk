<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\KonfigurasiBarang;
use App\Filament\Resources\ItemsResource\Pages;
use App\Models\Items;
use App\Models\CategoryItems;
use App\Models\GlobalFine;
use App\Models\TrxRentItem;
use App\Models\TrxRentItemDetail;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;

use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemsResource extends Resource
{
    protected static ?string $model = Items::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $cluster = KonfigurasiBarang::class;

    protected static ?string $recordTitleAttribute = 'items_name';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Barang')
                            ->schema([
                                Forms\Components\TextInput::make('items_name')
                                    ->label('Nama Barang')
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Sewa')
                                    ->required()
                                    ->prefix('Rp'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Deskripsi & Gambar')
                            ->schema([
                                Forms\Components\RichEditor::make('desc')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->toolbarButtons([
                                        'blockquote', 'bold', 'bulletList',
                                        'codeBlock', 'h2', 'h3', 'italic',
                                        'link', 'orderedList', 'redo',
                                        'strike', 'underline', 'undo',
                                    ])
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('image')
                                    ->label('Gambar')
                                    ->image()
                                    ->required()
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable()
                                    ->imagePreviewHeight('250')
                                    ->directory('items')
                                    ->preserveFilenames()
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg'])
                                    ->helperText('Format: PNG, JPG, JPEG. Maksimal 2MB'),                            ]),

                        Forms\Components\Section::make('Stock')
                            ->schema([
                                Forms\Components\TextInput::make('stock')
                                    ->label('Stock')
                                    ->hint('Stock produk utama')
                                    ->helperText('* Jika menambahkan stock per ukuran, stock produk utama harap diisi 0.')
                                    ->numeric()
                                    ->required()
                                    ->nullable(),
                                Forms\Components\Repeater::make('itemStock')
                                    ->relationship('itemStock')
                                    ->label('Stock per Ukuran')
                                    ->schema([
                                        Forms\Components\TextInput::make('size')
                                            ->label('Ukuran')
                                            ->required(),
                                        Forms\Components\TextInput::make('stock')
                                            ->label('Stock')
                                            ->required()
                                            ->numeric()
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Select::make('active')
                                    ->label('Status')
                                    ->options([
                                        true => 'Tersedia',
                                        false => 'Tidak Tersedia',
                                    ])
                                    ->native(false)
                                    ->default(true)
                                    ->required()
                            ]),

                        Forms\Components\Section::make('Kategori & Denda')
                            ->schema([
                                Forms\Components\Select::make('ctgr_items_id')
                                    ->label('Kategori Barang')
                                    ->preload()
                                    ->searchable()
                                    ->options(CategoryItems::where('active', true)
                                        ->pluck('ctgr_items_name', 'ctgr_items_id'))
                                    ->required(),
                                Forms\Components\Select::make('global_fine_id')
                                    ->label('Jenis Denda')
                                    ->preload()
                                    ->searchable()
                                    ->options(GlobalFine::pluck('fine_name', 'global_fine_id'))
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('items_name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.ctgr_items_name')
                    ->label('Kategori')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Sewa')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok Regular')
                    ->sortable()
                    ->alignment(Alignment::Right),

                Tables\Columns\TextColumn::make('totalStock')
                    ->label('Total Stock')
                    ->sortable()
                    ->getStateUsing(fn (Items $record) => $record->totalStock)
                    ->alignment(Alignment::Right),

                Tables\Columns\TextColumn::make('itemStock')
                    ->label('Stok per Ukuran')
                    ->badge()
                    ->getStateUsing(function (Items $record) {
                        // Mengambil data stok per ukuran
                        $stockData = $record->itemStock->map(function ($itemStock) {
                            return $itemStock->size . ' / ' . $itemStock->stock;
                        })->implode('<br>');

                        return $stockData ?: 'Tidak tersedia';
                    })
                    ->html()
                    ->alignment(Alignment::Center),

                Tables\Columns\IconColumn::make('active')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'ctgr_items_name')
                    ->label('Kategori')
                    ->multiple()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('active')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia')
                    ->native(false),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Stok Menipis')
                    ->query(function (Builder $query) {
                        return $query->whereRaw('(stock + COALESCE((
                            SELECT SUM(stock)
                            FROM item_stock
                            WHERE item_stock.items_id = items.items_id
                        ), 0)) <= ?', [10]);
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->before(function ($record) {
                            $activeRentals = TrxRentItem::whereHas('details', function ($query) use ($record) {
                                $query->where('items_id', $record->items_id);
                            })->where('status', 'D')->count();

                            if ($activeRentals > 0) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Barang ini sedang dalam penyewaan aktif. Tidak dapat diedit.')
                                    ->danger()
                                    ->send();

                                return false;
                            }
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->before(function ($record) {
                            $activeRentals = TrxRentItem::whereHas('details', function ($query) use ($record) {
                                $query->where('items_id', $record->items_id);
                            })->where('status', 'D')->count();

                            if ($activeRentals > 0) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Barang ini sedang dalam penyewaan aktif. Tidak dapat dihapus.')
                                    ->danger()
                                    ->send();

                                return false;
                            }

                            // Delete related rental details and update totals
                            DB::transaction(function () use ($record) {
                                // Get all rental details with this item
                                $details = TrxRentItemDetail::where('items_id', $record->items_id)->get();

                                foreach ($details as $detail) {
                                    $rental = $detail->trxRentItem;

                                    // Delete the detail
                                    $detail->delete();

                                    // If rental has no more details, delete it
                                    if ($rental->details()->count() === 0) {
                                        $rental->delete();
                                    } else {
                                        // Recalculate rental total
                                        $newTotal = $rental->details()->sum('sub_total');
                                        $rental->update(['total' => $newTotal]);
                                    }
                                }
                            });
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $activeRentals = TrxRentItem::whereHas('details', function ($query) use ($record) {
                                    $query->where('items_id', $record->items_id);
                                })->where('status', 'D')->count();

                                if ($activeRentals > 0) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Beberapa barang sedang dalam penyewaan aktif. Tidak dapat dihapus.')
                                        ->danger()
                                        ->send();

                                    return false;
                                }

                                // Handle cascade deletion for each record
                                DB::transaction(function () use ($record) {
                                    $details = TrxRentItemDetail::where('items_id', $record->items_id)->get();

                                    foreach ($details as $detail) {
                                        $rental = $detail->trxRentItem;
                                        $detail->delete();

                                        if ($rental->details()->count() === 0) {
                                            $rental->delete();
                                        } else {
                                            $rental->update(['total' => $rental->details()->sum('sub_total')]);
                                        }
                                    }
                                });
                            }
                        }),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['active' => false])),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
    public static function getModelLabel(): string
    {
        return 'Barang';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'items_name',
            'items_code',
            'desc',
            'category.ctgr_items_name',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->items_name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Kategori' => $record->category->ctgr_items_name,
            'Harga' => 'Rp ' . number_format($record->price, 0, ',', '.'),
            'Stok' => $record->totalStock,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItems::route('/create'),
            'edit' => Pages\EditItems::route('/{record}/edit'),
        ];
    }

}