<?php

namespace App\Filament\Resources\AppPushNotificationResource\Pages;

use App\Filament\Resources\AppPushNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppPushNotifications extends ListRecords
{
    protected static string $resource = AppPushNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
