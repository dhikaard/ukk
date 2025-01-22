<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryProductsResource\Pages;
use App\Filament\Resources\CategoryProductsResource\RelationManagers;
use App\Models\CategoryProducts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryProductsResource extends Resource
{
    protected static ?string $model = CategoryProducts::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('ctgr_products_name')
                ->required(),
            Forms\Components\Toggle::make('active')
                ->label('Aktif')
                ->visible(fn ($livewire) => $livewire instanceof Pages\EditCategoryProducts), // Hanya muncul saat edit
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ctgr_products_name')
                    ->searchable(),
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
        return 'Kategori Produk';
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
            'index' => Pages\ListCategoryProducts::route('/'),
            'create' => Pages\CreateCategoryProducts::route('/create'),
            'edit' => Pages\EditCategoryProducts::route('/{record}/edit'),
        ];
    }
}
