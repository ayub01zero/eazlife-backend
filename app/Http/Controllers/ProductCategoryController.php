<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryAtCompanyRequest;
use App\Models\ProductCategory;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductCategoryController extends Controller
{
    public function createAtCompany(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $productCategories = ProductCategory::all();

        return view('product-categories.create-at-company', ['company' => $company, 'productCategories' => $productCategories]);
    }

    public function storeAtCompany(StoreProductCategoryAtCompanyRequest $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $productCategoryId = $request->get('product_category');

        $existingProductCategory = $company->productCategories()->where('product_category_id', $productCategoryId)->first();

        if (is_null($existingProductCategory)) {
            $company->productCategories()->attach($productCategoryId);
        } else {
            // $existingProductCategory is not null
            // return redirect()->route('companies.products.index', ['company_id' => $company->id]);
        }

        return Redirect::route('companies.products.index', ['company' => $company]);
    }


}
