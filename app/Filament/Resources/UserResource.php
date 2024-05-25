<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Actions\ApproveUser;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Company;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getnavigationGroup(): string
    {
        return __('CRM'); 
    }
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 3;


    public static function getmodelLabel(): string
    {
        return __('User');
    }

    public static function getNavigationLabel(): string
    {
        return __('users');
    }
    public static function getPluralModelLabel(): string
    {
        return __('User');
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'Super Admin', 'App']);
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label(__('user_name'))
                    ->required()->autofocus(),
                TextInput::make('email')
                ->label(__('email_address'))
                    ->required(),
                TextInput::make('phone_number')
                ->label(__('phone_number'))
                    ->required(),
                Select::make('companies')
                ->label(__('work at'))
                    ->searchable()
                    ->relationship(titleAttribute: 'name'),
                Checkbox::make('approved')
                ->label(__('approve'))
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('user_name')),
                TextColumn::make('creator.name')->label(__('added_by'))->default('-'),
                IconColumn::make('approved')->label(__('approve'))
                    ->boolean(),
            ])
            ->defaultSort('approved', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // public function approve(User $user)
    // {
    //     if ($user->approved) {
    //         return back()->with('error', 'This user has already been approved.');
    //     }

    //     $user->update(['approved' => true]);

    //     $token = Password::createToken($user);
    //     $user->sendPasswordResetNotification($token);

    //     return back()->with('success', 'User has been approved and notified via email.');
    // }
}
