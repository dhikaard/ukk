<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlobalFineResource\Pages;
use App\Models\GlobalFine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GlobalFineResource extends Resource
{
    protected static ?string $model = GlobalFine::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fine_name')
                    ->required(),
                Forms\Components\TextInput::make('fine_percentage')
                    ->required()
                    ->numeric(),
                Forms\Components\TimePicker::make('time_limit')
                    ->label('Waktu Limit')
                    ->native(false)
                    ->weekStartsOnMonday()
                    ->closeOnDateSelection()
                    ->reactive()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fine_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fine_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_limit'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlobalFines::route('/'),
            'create' => Pages\CreateGlobalFine::route('/create'),
            'edit' => Pages\EditGlobalFine::route('/{record}/edit'),
        ];
    }
}
