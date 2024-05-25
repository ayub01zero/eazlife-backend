<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Extra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ExtrasRelationManager extends RelationManager
{
    protected static string $relationship = 'extras';

    public static function getPluralModelLabel(): string
    {
        return __('extras');
    }
    public static function getmodelLabel(): string
    {
        return __('extras');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label(__('name')),
                Forms\Components\TextInput::make('description')
                ->label(__('Description')),
                Forms\Components\TextInput::make('price')
                    ->label(__('price')),
                Forms\Components\TextInput::make('max_quantity')
                    ->label(__('max_quantity')),
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
                Tables\Actions\EditAction::make()->after(function (Extra $record) {
                    $editDuplicateId = $record->edit_duplicate_id;

                    if ($editDuplicateId) {
                        $record->edit_duplicate_id = null;
                        $record->save();
                        Extra::where('id', $editDuplicateId)->delete();
                    }
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
