<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlotPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function manageProduct(User $user, Slot $slot, Company $company)
    {
        return $user->id === $company->user_id && $slot->company_id === $company->id;
    }
}
