<?php

namespace App\Filament\Resources\AppPushNotificationResource\Pages;

use App\Filament\Resources\AppPushNotificationResource;
use App\Models\User;
use App\Notifications\AppPushNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateAppPushNotification extends CreateRecord
{
    protected static string $resource = AppPushNotificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $admin = auth()->user();
        $data['created_by'] = $admin->id;

        $users = User::whereNotNull('push_token')->get();

        foreach ($users as $user) {
            $user->notify(new AppPushNotification($data['title'], $data['body']));
        }

        $data['was_successful'] = true;

        return $data;
    }
}
