<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\KonfigurasiBarang;
use App\Filament\Resources\ItemsResource\Pages;
use App\Models\Items;
use App\Models\CategoryItems;
use App\Models\GlobalFine;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;

use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;

class ItemsResource extends Resource
{
    protected static ?string $model = Items::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $cluster = KonfigurasiBarang::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('items_name')
                    ->label('Nama Barang')
                    ->required(),
                Forms\Components\Select::make('ctgr_items_id')
                    ->label('Kategori Barang')
                    ->preload()
                    ->reactive()
                    ->options(CategoryItems::where('active', true)->pluck('ctgr_items_name', 'ctgr_items_id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('ctgr_items_name')
                            ->label('Nama Kategori')
                            ->required(),
                        Forms\Components\Toggle::make('active')
                            ->label('Aktif')
                            ->default(true),
                        ])
                    ->createOptionUsing(function (array $data) {
                        $category = CategoryItems::create([
                            'ctgr_items_name' => $data['ctgr_items_name'],
                            'active' => $data['active'] ?? true,
                        ]);
                        return $category->id;
                        })
                    ->required(),
                    Forms\Components\Select::make('active')
                    ->label('Status')
                    ->options([
                        true => 'Tersedia',
                        false => 'Tidak Tersedia',
                    ])
                    ->default(true)
                    ->required()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditItems),
                Forms\Components\Select::make('global_fine_id')
                    ->label('Jenis Denda')
                    ->preload()
                    ->searchable()
                    ->options(GlobalFine::pluck('fine_name', 'global_fine_id')),
                Forms\Components\TextInput::make('price')
                    ->label('Harga Sewa')
                    ->required()
                    ->prefix('Rp'),
                Forms\Components\Section::make('Deskripsi dan Gambar')
                    ->schema([
                        Forms\Components\RichEditor::make('desc')
                        ->label('Deskripsi')
                        ->columnSpan(2)
                        ->required()
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ]),
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar')
                        ->image()
                        ->required(),
                    ]),
                Forms\Components\Section::make('Ukuran dan Stock')
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
                                    ->required()
                                    ->label('Size')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('stock')
                                    ->label('Stock')
                                    ->required()
                                    ->numeric()
                            ])
                            ->collapsible()
                            ->cloneable()
                            ->defaultItems(0)
                            ->columns(2)
                            ->grid(2)
                            ->reorderable()
                            ->columnSpanFull()
                            ->addActionAlignment(Alignment::Start),
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->size(55)
                    ->circular(),
                Tables\Columns\TextColumn::make('items_name')
                    ->label('Nama Barang')
                    ->description(fn (Items $record): string => $record->items_code)
                    ->searchable(['items_name', 'items_code'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('ctgr_items.ctgr_items_name')
                    ->label('Kategori Barang'),
                Tables\Columns\TextColumn::make('totalStock')
                    ->label('Total Stock')
                    ->sortable()
                    ->getStateUsing(fn (Items $record) => $record->totalStock)
                    ->alignment(Alignment::Center),
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
                    ->html(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('global_fine_id')
                    ->label('Denda per Hari')
                    ->numeric(),
                Tables\Columns\TextColumn::make('active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Tersedia' : 'Tidak Tersedia')
                    ->color(fn ($state) => $state == 1 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchOnBlur()
            ->striped()
            ->searchPlaceholder('Cari nama barang (Kode Barang, Nama Barang)')
            ->filters([
                SelectFilter::make('active')
                    ->label('Status')
                    ->options([
                        true => 'Tersedia',
                        false => 'Tidak Tersedia'
                    ]),
                SelectFilter::make('ctgr_items')
                    ->label('Kategori')
                    ->searchable()
                    ->preload()
                    ->relationship('ctgr_items', 'ctgr_items_name'),
            ])
            ->filtersFormWidth(MaxWidth::ExtraSmall)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ]),
            ]);
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

    public static function getRelations(): array
    {
        return [
            //
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