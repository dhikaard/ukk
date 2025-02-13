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
use Illuminate\Database\Eloquent\Model;

class CategoryItemsResource extends Resource
{
    protected static ?string $model = CategoryItems::class;

    protected static ?string $cluster = KonfigurasiBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'ctgr_items_name';

    protected static ?int $navigationSort = 1;

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
                    ->label('Nama Kategori')
                    ->icon('heroicon-m-tag')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Barang')
                    ->counts('items')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->searchPlaceholder('Cari kategori barang...')
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Status')
                    ->placeholder('Semua Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'ctgr_items_name',
        ];
    }
    
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->ctgr_items_name;
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Jumlah Barang' => $record->count() . ' barang',
            'Status' => $record->active ? 'Aktif' : 'Tidak Aktif',
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
