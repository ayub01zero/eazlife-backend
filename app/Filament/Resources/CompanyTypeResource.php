<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyTypeResource\Pages;
use App\Filament\Resources\CompanyTypeResource\RelationManagers;
use App\Models\CompanyType;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class CompanyTypeResource extends Resource
{
    protected static ?string $model = CompanyType::class;

    public static function getnavigationGroup(): string
    {
        return __('App');
    }

    public static function getmodelLabel(): string
    {
        return __('CompanyType');
    }
   public static function getPluralModelLabel(): string
    {
        return __('CompanyType');
    }
    
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 13;

    public static function getNavigationLabel(): string
    {
        return __('company type');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('name')),
                MediaPicker::make('image_id')
                ->label(__('mapmarker image')) 
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label(__('name')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCompanyTypes::route('/'),
            // 'create' => Pages\CreateCompanyType::route('/create'),
            'edit' => Pages\EditCompanyType::route('/{record}/edit'),
        ];
    }
}
