<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\Pages\ListCompanies;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\FulfillmentType;
use App\Models\User;
use DragonCode\PrettyArray\Services\File;
use DragonCode\Support\Facades\Helpers\Boolean;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use Filament\Resources\RelationManagers\RelationGroup;


class CompanyResource extends Resource
{

    protected static ?string $model = Company::class;

    public static function getnavigationGroup(): string
    {
        return __('CRM'); 
    }

    public static function getmodelLabel(): string
    {
        return __('company');
    }

    public static function getPluralModelLabel(): string
    {
        return __('company');
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    public static function getNavigationLabel(): string
    {
        return __('company');
    }

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $editDuplicateIds = DB::table('companies')
            ->whereNotNull('edit_duplicate_id')
            ->pluck('edit_duplicate_id');

        // Return the query excluding those products
        return parent::getEloquentQuery()
            ->whereNotIn('id', $editDuplicateIds);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo_path')
                    ->image()
                    ->directory('logos')
                    ->label(__('logo')),
                FileUpload::make('banner_path')
                ->label(__('banner path'))
                    ->image()
                    ->directory('banners'),
                TextInput::make('name')
                    ->required()->autofocus()->label(__('name')),
                Select::make('category_id')
                    ->options(CompanyCategory::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple()
                    ->label(__('categories'))
                    ->relationship('categories', 'name'),
                TextInput::make('address')->label(__('address')),
                Select::make('country')
                    ->label(__('country'))
                    ->options(['netherlands' => 'Nederland']),
                TextInput::make('city')->label(__('city')),
                TextInput::make('state')->label(__('state')),
                TextInput::make('zip_code')->label(__('zip_code')),
                Checkbox::make('popular')->label(__('popular')),
                Select::make('user_id')
                ->label(__('owner'))
                    ->searchable()
                    ->relationship(name: 'user', titleAttribute: 'email'),
                Select::make('users')
                    ->label(__('Employees'))
                    ->searchable()
                    ->multiple()
                    ->relationship(titleAttribute: 'email'),
                TextInput::make('radius')->integer()
                ->label(__('radius'))
                    ->suffix('km'),
                TextInput::make('delivery_cost')
                ->label(__('delivery_cost'))
                    ->suffix('€'),
                Select::make('fulfillmentTypes')
                ->label(__('flow'))
                    ->multiple()
                    ->relationship(titleAttribute: 'name'),
                Checkbox::make('is_approved')
                    ->default(true)
                    ->label(__('is approved')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('name')),
                TextColumn::make('user.name')->label(__('owner')),
                IconColumn::make('user.approved')->boolean()->label(__('owner_approved')),
                ViewColumn::make('uploaded_inventory')
                    ->view('filament.tables.columns.inventory')->label(__('inventory')),
                ViewColumn::make('logo')
                    ->view('filament.tables.columns.logo')->label(__('logo')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        
        return [
            // ...
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\SlotsRelationManager::class,
            RelationManagers\OrdersRelationManager::class,
    
            // ...
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
