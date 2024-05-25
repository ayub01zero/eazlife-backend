<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminUserResource\Pages;
use App\Filament\Resources\AdminUserResource\RelationManagers;
use App\Models\AdminUser;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Admins';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function canViewAny(): bool
    {
        return Auth::user()->isSuperAdmin();
    }


    // public static function getnavigationGroup(): string
    // {
    //     return __('CRM'); 
    // }

    public static function getmodelLabel(): string
    {
        return __('admin');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin');
    }

   

    public static function getNavigationLabel(): string
    {
        return __('admin');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()->autofocus()
                    ->label(__('name')),
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
            'index' => Pages\ListAdminUsers::route('/'),
            'create' => Pages\CreateAdminUser::route('/create'),
            'edit' => Pages\EditAdminUser::route('/{record}/edit'),
        ];
    }
}
