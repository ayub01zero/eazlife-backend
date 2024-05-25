<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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

    public function manageProduct(User $user, Product $product, Company $company)
    {
        return $user->id === $company->user_id && $product->company_id === $company->id && $product->is_approved === 1;
    }
}
