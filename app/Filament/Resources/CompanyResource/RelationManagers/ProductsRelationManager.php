<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Models\Company;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public static function getPluralModelLabel(): string
    {
        return __('product');
    }
    public static function getmodelLabel(): string
    {
        return __('product');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label(__('name'))
                    ->required()->autofocus(),
                MediaPicker::make('image_id')
                ->label(__('choose_image'))
                    ->required(),
                Select::make('product_category_id')
                    ->label(__('category'))
                    ->options(ProductCategory::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship('productCategory', 'name')
                    ->required(),
                TextInput::make('price')
                    ->label(__('price'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('name')),
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
