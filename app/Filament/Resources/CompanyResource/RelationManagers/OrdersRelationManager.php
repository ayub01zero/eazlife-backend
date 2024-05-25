<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    
    public static function getPluralModelLabel(): string
    {
        return __('orders');
    }
    public static function getmodelLabel(): string
    {
        return __('orders');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->label(__('address')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('address')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->label(__('user')),
                Tables\Columns\TextColumn::make('address')
                ->label(__('address')),
                 Tables\Columns\TextColumn::make('created_at')->dateTime()
                 ->label(__('created_at')),
                Tables\Columns\TextColumn::make('created_at')->dateTime()
                ->label(__('created_at')),
                // Tables\Columns\TextColumn::make('delivery_at')->dateTime(),
                Tables\Columns\TextColumn::make('sent_at')->dateTime()
                ->label(__('sent_at')),



            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
