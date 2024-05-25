<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppUserResource\Pages;
use App\Filament\Resources\AppUserResource\RelationManagers;
use App\Models\AppUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class AppUserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getnavigationGroup(): string
    {
        return __('App');
    }
    public static function getmodelLabel(): string
    {
        return __('userapp');
    }

    public static function getPluralModelLabel(): string
{
    return __('userapp');
}
    public static function getNavigationLabel(): string
    {
        return __('usercompany');
    }
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 9;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'App');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label(__('name'))
                    ->required()->autofocus(),
                TextInput::make('email')
                    ->required()
                    ->label(__('email')),
                TextInput::make('phone_number')
                    ->required()
                    ->label(__('phone_number')),
                TextInput::make('password')
                    ->password()
                    ->label(__('password'))
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label(__('name')),
                TextColumn::make('email')
                ->label(__('email')),
                TextColumn::make('phone_number')
                ->label(__('phone_number')),
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
            'index' => Pages\ListAppUsers::route('/'),
            'create' => Pages\CreateAppUser::route('/create'),
            'edit' => Pages\EditAppUser::route('/{record}/edit'),
        ];
    }
}
