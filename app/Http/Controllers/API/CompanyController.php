<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class CompanyController extends Controller
{
    public function getCompanyTypes()
    {
        $types = CompanyType::all();

        $types->each(function ($type) {
            $type->marker_image_path = env('MEDIA_URL') . $type->marker_image_path;
        });

        return response()->json($types);
    }

    public function getCompaniesNearLocation(Request $request)
    {
        $this->validate($request, [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'typeId' => 'exists:company_types,id|numeric',
        ]);

        $longitude = $request->input('lng');
        $latitude = $request->input('lat');
        $typeId = $request->input('typeId', null);

        $companies = Company::select(
            '*',
            DB::raw("ROUND(ST_Distance_Sphere(location, ST_GeomFromText('POINT({$longitude} {$latitude})')) / 1000, 1) as distance"),
            DB::raw('ST_Y(location) as latitude'),
            DB::raw('ST_X(location) as longitude')
        )->whereNotNull('banner_path')->where('online', true)->where('is_approved', true);

        if ($typeId) {
            $companies = $companies->whereHas('companyType', function ($query) use ($typeId) {
                $query->where('id', $typeId);
            });
        }

        $companies = $companies
            ->havingRaw("distance <= radius")
            ->orderByDesc('popular')
            ->orderBy('distance', 'asc')
            ->with('categories')
            ->get();

        foreach ($companies as $company) {
            $company->delivery = $company->calculateDeliveryTime($company->distance);
            $company->pickup = $company->calculatePickupTime();

            if ($company->distance == 0) {
                $company->distance = 1;
            }
        }

        if (empty($companies)) {
            return response()->json(['error' => 'No companies'], 404);
        }

        $categories = collect();
        foreach ($companies as $company) {
            foreach ($company->categories as $category) {
                $categories->push($category);
            }
        }
        $uniqueCategories = $categories->unique('id')->values();

        return response()->json(['companies' => $companies->makeHidden('location'), 'categories' => $uniqueCategories]);
    }

    public function getCompanyDetails(Request $request)
    {
        $this->validate($request, ['id' => 'exists:companies,id|required|numeric']);

        $company = Company::with(['products' => function ($query) {
            $query->where('is_approved', true);
        }, 'products.productCategory', 'fulfillmentTypes'])
            ->find($request->input('id'));

        foreach ($company->products as $product) {
            $mediaLibraryItem = MediaLibraryItem::find($product->image_id);
            $spatieMediaModel = $mediaLibraryItem->getItem();
            $url = $spatieMediaModel->getUrl();

            $product->image_url = $url;
            $product->category = $product->productCategory->name;
            $product->extrasCount = $product->extras->count();
            unset($product->productCategory); // Remove original relation name
        }

        $fulfillmentTypes = [];
        foreach ($company->fulfillmentTypes as $type) {
            $fulfillmentTypes[] = $type->name;
        }

        $company->fulfillment_types = $fulfillmentTypes;
        unset($company->fulfillmentTypes);

        $company->group = $company->typeIsGroup();

        $company->info = [
            'openingHours' => $company->openingTimes,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
        ];

        return response()->json($company->makeHidden('location'));
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'query' => 'required|string|max:255',
        ]);

        $query = $request->input('query');

        $companiesByName = Company::where('name', 'like', "%{$query}%")
            ->whereNotNull('banner_path')
            ->get();

        $companiesByAddress = Company::where('address', 'like', "%{$query}%")
            ->whereNotNull('banner_path')
            ->get();

        $companiesByProduct = Company::whereHas('products', function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'like', "%{$query}%");
        })
            ->whereNotNull('banner_path')
            ->get();

        $companiesByProductCategory = Company::whereHas('productCategories', function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'like', "%{$query}%");
        })
            ->whereNotNull('banner_path')
            ->get();

        $companiesByCompanyType = Company::whereHas('companyType', function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'like', "%{$query}%");
        })
            ->whereNotNull('banner_path')
            ->get();

        $companies = $companiesByName
            ->merge($companiesByAddress)
            ->merge($companiesByProduct)
            ->merge($companiesByProductCategory)
            ->merge($companiesByCompanyType);

        foreach ($companies as $company) {
            $company->delivery = $company->calculateDeliveryTime();
            $company->pickup = $company->calculatePickupTime();
        }

        return response()->json($companies->makeHidden('location'));
    }
}
