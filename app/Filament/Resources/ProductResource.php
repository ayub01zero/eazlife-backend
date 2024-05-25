<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategory;
use BladeUI\Icons\Components\Icon;
use DragonCode\Support\Facades\Helpers\Boolean;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getnavigationGroup(): string
    {
        return __('CRM'); 
    }

    public static function getPluralModelLabel(): string
    {
        return __('Product');
    }

    public static function getmodelLabel(): string
    {
        return __('Product');
    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;
    public static function getNavigationLabel(): string
    {
        return __('product');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()->autofocus()
                    ->label(__('name')),
                MediaPicker::make('image_id')
                ->label(__('choose_image'))
                    ->required(),
                Select::make('product_category_id')
                ->label(__('category'))
                    ->options(ProductCategory::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship('productCategory', 'name')
                    ->required(),
                Select::make('company_id')
                ->label(__('company'))
                    ->options(Company::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship('company', 'name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->label(__('price')),
                    TextInput::make('description')
                    ->required()
                    ->label(__('Description')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('name')),
                TextColumn::make('company.name')->label(__('company')),
            ])
            ->defaultSort('is_approved', 'asc')
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ExtrasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
