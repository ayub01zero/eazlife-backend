<?php

namespace App\Filament\Resources\AppUserResource\Pages;

use App\Filament\Resources\AppUserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppUser extends CreateRecord
{
    protected static string $resource = AppUserResource::class;

    protected function afterCreate(): void
    {
        $user = User::find($this->record->id);
        $user->roles()->attach(5);
        $user->save();
    }
}
