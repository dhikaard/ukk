<?php

namespace App\Filament\Resources;

use App\Filament\NavigationGroups;
use App\Filament\Resources\GlobalFineResource\Pages;
use App\Models\GlobalFine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;

class GlobalFineResource extends Resource
{
    protected static ?string $model = GlobalFine::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $recordTitleAttribute = 'fine_name';
    protected static ?string $navigationGroup = NavigationGroups::RENT_MANAGEMENT;
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Denda')
                    ->description('Konfigurasi perhitungan denda keterlambatan')
                    ->schema([
                        Forms\Components\TextInput::make('fine_name')
                            ->label('Nama Denda')
                            ->required()
                            ->placeholder('Contoh: Denda Standar')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('fine_percentage')
                                    ->label('Persentase Denda')
                                    ->required()
                                    ->numeric()
                                    ->suffix('%')
                                    ->placeholder('0-100')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->helperText('Persentase denda dari harga sewa'),

                                Forms\Components\TimePicker::make('time_limit')
                                    ->label('Batas Waktu')
                                    ->required()
                                    ->seconds(false)
                                    ->helperText('Batas waktu pengembalian sebelum denda')
                                    ->native(false),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fine_name')
                    ->label('Nama Denda')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('fine_percentage')
                    ->label('Persentase')
                    ->numeric()
                    ->suffix('%')
                    ->sortable()
                    ->alignment(Alignment::Center),

                Tables\Columns\TextColumn::make('time_limit')
                    ->label('Batas Waktu')
                    ->time()
                    ->alignment(Alignment::Center),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Digunakan Pada')
                    ->counts('items')
                    ->alignment(Alignment::Center),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->disabled(fn (GlobalFine $record) => $record->items()->exists()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlobalFines::route('/'),
            'create' => Pages\CreateGlobalFine::route('/create'),
            'edit' => Pages\EditGlobalFine::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Konfigurasi Denda';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Konfigurasi Denda';
    }
}