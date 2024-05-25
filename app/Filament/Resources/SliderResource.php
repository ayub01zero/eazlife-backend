<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\MediaLibrary\Media\Components\MediaPicker;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    public static function getnavigationGroup(): string
    {
        return __('App');
    }
    public static function getmodelLabel(): string
    {
        return __('Slider');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Slider');
    }
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?int $navigationSort = 11;
    public static function getNavigationLabel(): string
    {
        return __('slider');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()->autofocus()
                    ->label(__('Title')),
                Repeater::make('slides')
                ->label(__('Slides'))
                    ->relationship()
                    ->schema([
                        TextInput::make('title')
                        ->label(__('Title')),
                        TextInput::make('description')
                        ->label(__('Description')),
                        TextInput::make('link')
                        ->label(__('Link')),
                        Select::make('company_id')
                        ->label(__('company'))
                            ->relationship(name: 'company', titleAttribute: 'name')->searchable(['name']),
                        Select::make('product_id')
                        ->label(__('product'))
                            ->relationship(name: 'product', titleAttribute: 'name')->searchable(['name']),
                        MediaPicker::make('image_id')
                        ->label(__('choose_image'))
                            ->required(),
                    ])
                    ->cloneable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                ->label(__('Title'))
                ,
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
