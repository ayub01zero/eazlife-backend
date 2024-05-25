<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyCategoryResource\Pages;
use App\Filament\Resources\CompanyCategoryResource\RelationManagers;
use App\Models\CompanyCategory;
use App\Models\CompanyType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyCategoryResource extends Resource
{
    protected static ?string $model = CompanyCategory::class;

    public static function getnavigationGroup(): string
    {
        return __('category'); 
    }

    public static function getmodelLabel(): string
    {
        return __('CompanyCategory');
    }

    public static function getPluralModelLabel(): string
    {
        return __('CompanyCategory');
    }
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('company category');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()->autofocus()
                ->label(__('name')),
            Select::make('company_type_id')
            ->label(__('type'))
                ->options(CompanyType::all()->pluck('name', 'id'))
                ->relationship('type', 'name'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('name')),
                TextColumn::make('type.name')->label(__('type'))
            ])
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
            'index' => Pages\ListCompanyCategories::route('/'),
            'create' => Pages\CreateCompanyCategory::route('/create'),
            'edit' => Pages\EditCompanyCategory::route('/{record}/edit'),
        ];
    }
}
