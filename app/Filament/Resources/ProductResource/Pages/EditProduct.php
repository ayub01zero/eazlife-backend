<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Company;
use App\Models\Product;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $duplicateData = Product::whereId($data['edit_duplicate_id'])->latest()->first();

        if ($duplicateData) {
            foreach ($duplicateData->getAttributes() as $key => $value) {
                if ($data[$key] != $value) {
                    $data[$key] = $value;
                }
            }
        }

        $data['is_approved'] = true;

        return $data;
    }

    protected function afterSave(): void
    {
        $editDuplicateId = $this->record->edit_duplicate_id;

        // After saving, check if we need to delete the duplicate
        if ($editDuplicateId) {
            $this->record->edit_duplicate_id = null;
            $this->record->save();
            Product::where('id', $editDuplicateId)->delete();
        }

        if ($this->record->product_category_id) {
            $company = Company::find($this->record->company_id);
            if (!$company->productCategories->contains($this->record->product_category_id)) {
                $company->productCategories()->attach($this->record->product_category_id);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label(__('Delete Product')),
        ];
    }
}
