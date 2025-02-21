<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;


class ViewCompany extends ViewRecord
{
    
    protected static string $resource = CompanyResource::class;

    protected static string $view = 'filament.resources.companies.view';
}
