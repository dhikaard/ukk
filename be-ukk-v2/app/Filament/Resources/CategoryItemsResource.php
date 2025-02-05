<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryItemsResource\Pages;
use App\Models\CategoryItems;
use App\Filament\Clusters\KonfigurasiBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryItemsResource extends Resource
{
    protected static ?string $model = CategoryItems::class;

    protected static ?string $cluster = KonfigurasiBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('ctgr_items_name')
                ->required(),
            Forms\Components\Toggle::make('active')
                ->label('Aktif')
                ->required()
                ->inline(false)
                ->visible(fn ($livewire) => $livewire instanceof Pages\EditCategoryItems),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ctgr_items_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kategori Barang'),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchPlaceholder('Cari Kategori Barang')
            ->striped()
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
        return 'Kategori Barang';
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
            'index' => Pages\ListCategoryItems::route('/'),
            'create' => Pages\CreateCategoryItems::route('/create'),
            'edit' => Pages\EditCategoryItems::route('/{record}/edit'),
        ];
    }
}
