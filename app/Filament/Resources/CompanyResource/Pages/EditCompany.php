<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\Company;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $duplicateData = Company::whereId($data['edit_duplicate_id'])->latest()->first();

        $duplicateData && $duplicateData = $duplicateData->getAttributes();
        unset($duplicateData['location']);

        if ($duplicateData) {
            foreach ($duplicateData as $key => $value) {
                if ($data[$key] != $value) {
                    $data[$key] = $value;
                }
            }
        }

        $data['is_approved'] = true;

        return $data;
    }

   
    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
