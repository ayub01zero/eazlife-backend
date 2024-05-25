<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppPushNotificationResource\Pages;
use App\Filament\Resources\AppPushNotificationResource\RelationManagers;
use App\Models\AppPushNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppPushNotificationResource extends Resource
{
    protected static ?string $model = AppPushNotification::class;

    // protected static ?string $navigationGroup = __('app');
    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?int $navigationSort = 10;
    public static function getnavigationGroup(): string
    {
        return __('App');
    }
    public static function getmodelLabel(): string
    {
        return __('AppPushNotification');
    }

    public static function getPluralModelLabel(): string
{
    return __('AppPushNotification');
}
    public static function getNavigationLabel(): string
    {
        return __('push notification');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->label(__('Title'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('body')
                ->label(__('Body'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('body')
                ->label(__('Body'))
                    ->searchable(),
                IconColumn::make('was_successful')
                ->label(__('Was Successful'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('creator.name')->label('Creator')
                ->label(__('Creator'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Created At'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAppPushNotifications::route('/'),
            'create' => Pages\CreateAppPushNotification::route('/create'),
            // 'edit' => Pages\EditAppPushNotification::route('/{record}/edit'),
        ];
    }
}
