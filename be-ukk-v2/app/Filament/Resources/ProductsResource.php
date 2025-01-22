<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\Products;
use App\Models\CategoryProducts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('products_name')
                    ->required(),
                Forms\Components\Select::make('ctgr_products_id')
                    ->label('Kategori Produk')
                    ->options(CategoryProducts::where('active', true)->pluck('ctgr_products_name', 'ctgr_products_id'))
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('desc')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock (Jika tidak menggunakan ukuran)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('fine_bill')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Repeater::make('productStock')
                    ->relationship('productStock')
                    ->label('Ukuran & Stock')
                    ->schema([
                        Forms\Components\TextInput::make('size')
                            ->required()
                            ->label('Size')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->label('Stock')
                            ->numeric()
                            ->columnSpan(1),
                    ])
                    ->collapsible()
                    ->defaultItems(0)
                    ->columns(2)  // Membuat dua kolom (untuk size dan stock)
                    ->columnSpanFull() // Menyesuaikan kolom
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('products_name')
                    ->description(fn (Products $record): string => $record->products_code)
                    ->searchable(),
                Tables\Columns\TextColumn::make('ctgr_products.ctgr_products_name')
                    ->numeric()
                    ->sortable(),
                // Menampilkan total stock dan ukuran/stok productStock dalam satu deskripsi
                Tables\Columns\TextColumn::make('totalStock')
                ->label('Total Stock')
                ->wrapHeader()
                ->description(function (Products $record) {
                    $record->load('productStock');
                    $stockData = $record->productStock->map(function ($productStock) {
                        return "{$productStock->stock} | {$productStock->size}";
                    })->implode(' | ');
                    
                    return $stockData ? 'Size: ' . $stockData : 'Stok per ukuran tidak tersedia.';
                }),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fine_bill')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }    

    public static function getModelLabel(): string
    {
        return 'Produk';
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('active', true);
    }


}
