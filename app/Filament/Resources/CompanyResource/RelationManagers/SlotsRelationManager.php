<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';

    public static function getPluralModelLabel(): string
    {
        return __('slot');
    }
    public static function getmodelLabel(): string
    {
        return __('slot');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('date_time')
                ->label(__('date_time'))
                    ->required()
                    ->label('Duration (minutes)'),
                Forms\Components\TextInput::make('capacity')
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date_time')
            ->columns([
                Tables\Columns\TextColumn::make('date_time')
                ->label(__('date_time')),
                Tables\Columns\TextColumn::make('duration')
                ->label(__('duration')),
                Tables\Columns\TextColumn::make('capacity')
                ->label(__('capacity')),
                Tables\Columns\IconColumn::make('reservation')->boolean()->label(__('reserved')),
                Tables\Columns\TextColumn::make('reservation.comment')->label(__('comment')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
