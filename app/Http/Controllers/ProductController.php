<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductCategory;
use App\Models\Image;
use App\Models\Product;
use App\Models\Company;
use App\Models\Extra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $productCategory = $request->input('product_category');

        $editDuplicateIds = DB::table('products')
            ->whereNotNull('edit_duplicate_id')
            ->pluck('edit_duplicate_id');

        if ($productCategory) {
            $products = Product::whereCompanyId($company->id)
                ->whereProductCategoryId($productCategory)
                ->whereNotIn('id', $editDuplicateIds)
                ->get();
        } else {
            $products = Product::whereCompanyId($company->id)
                ->whereNotIn('id', $editDuplicateIds)
                ->get();
        }

        $productCategories = $company->productCategories;

        return view('products.index', ['company' => $company, 'productCategoryId' => $productCategory, 'products' => $products, 'productCategories' => $productCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $productCategoryId = $request->input('product_category');

        if ($productCategoryId) {
            $productCategoryName = ProductCategory::find($productCategoryId)->name;
        } else {
            $productCategoryName = null;
        }

        $productCategories = $company->productCategories;
        $images = MediaLibraryItem::all();

        return view('products.create', ['company' => $company, 'productCategoryName' => $productCategoryName, 'productCategories' => $productCategories, 'images' => $images]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $request['product_category_id'] = ProductCategory::whereName($request->get('product_category'))->first()->id;
        $request['company_id'] = $company->id;

        Product::create($request->all());

        return Redirect::route('companies.products.index', ['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, Product $product)
    {
        $this->authorize('manageProduct', [$product, $company]);

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Company $company, Product $product)
    {
        $this->authorize('manageProduct', [$product, $company]);

        $productCategoryId = $request->input('product_category');

        if ($productCategoryId) {
            $productCategoryName = ProductCategory::find($productCategoryId)->name;
        } else {
            $productCategoryName = null;
        }

        $productCategories = $company->productCategories;
        $images = MediaLibraryItem::all();

        $duplicatedProduct = Product::find($product->edit_duplicate_id);

        $product->load('extras');

        // $allExtras = $product->extras()->get();

        // // Identify which extras are edited versions
        // $editDuplicateIds = $allExtras->pluck('edit_duplicate_id')->filter()->unique();

        // // Prepare extras, distinguishing between original and edited versions
        // $extras = $allExtras->reject(function ($extra) use ($editDuplicateIds) {
        //     // Exclude extras that are edits themselves
        //     return $editDuplicateIds->contains($extra->id);
        // })->map(function ($originalExtra) use ($allExtras) {
        //     // Attempt to find an edited version of this extra
        //     $editedExtra = $allExtras->firstWhere('edit_duplicate_id', $originalExtra->id);
        //     return [
        //         'original' => $originalExtra,
        //         'edited' => $editedExtra
        //     ];
        // });

        return view('products.edit', [
            'company' => $company, 'productCategoryName' => $productCategoryName, 'productCategories' => $productCategories, 'images' => $images, 'product' => $product, 'duplicatedProduct' => $duplicatedProduct,
            //  'extras' => $extras
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Company $company, Product $product)
    {
        $this->authorize('manageProduct', [$product, $company]);

        // Handling product edits
        $duplicateProduct = Product::where('id', $product->edit_duplicate_id)->first();

        if ($duplicateProduct) {
            // Update the existing duplicate with new edits
            $duplicateProduct->fill($request->except(['extras', 'edit_duplicate_id']));
            $duplicateProduct->save();
        } else {
            // Create a new duplicate if none exists
            $editedProduct = $product->replicate();
            $editedProduct->fill($request->except(['extras']));
            $editedProduct->edit_duplicate_id = null; // Since this is a new edit
            $editedProduct->save();

            // Link the original product to the new duplicate
            $product->edit_duplicate_id = $editedProduct->id;
            $product->save();
        }

        // Handling extras
        // if ($request->has('extras')) {
        //     foreach ($request->get('extras') as $extraData) {
        //         $existingExtra = $product->extras()->where('name', $extraData['name'])->first();

        //         if ($existingExtra) {
        //             // Check if a duplicate already exists
        //             $duplicateExtra = Extra::where('id', $existingExtra->edit_duplicate_id)->first();

        //             if ($duplicateExtra) {
        //                 // Duplicate exists, update it
        //                 $duplicateExtra->fill($extraData);
        //                 $duplicateExtra->save();
        //                 $duplicateId = $duplicateExtra->id;
        //             } else {
        //                 // No duplicate exists, create a new one
        //                 $editedExtra = $existingExtra->replicate();
        //                 $editedExtra->fill($extraData);
        //                 $editedExtra->edit_duplicate_id = null; // This is a new edit, so no duplicate id yet
        //                 $editedExtra->save();
        //                 $duplicateId = $editedExtra->id;
        //             }

        //             // Link the original extra to the new duplicate
        //             $existingExtra->edit_duplicate_id = $duplicateId;
        //             $existingExtra->save();
        //         } else {
        //             // Extra does not exist, create a new one as unapproved
        //             $newExtra = new Extra($extraData);
        //             $newExtra->product_id = $product->id;
        //             $newExtra->is_approved = false; // Mark as unapproved
        //             $newExtra->save();
        //         }
        //     }
        // }

        if ($request->has('extras')) {
            $newExtras = $request->input('extras');

            // Get the current extras and their IDs
            $currentExtras = $product->extras;
            $currentExtraIds = $currentExtras->pluck('id')->toArray();

            // Iterate through the new extras
            foreach ($newExtras as $index => $extraData) {
                $extraFields = [
                    'name' => $extraData['name'],
                    'price' => $extraData['price'],
                    'max_quantity' => $extraData['max_quantity'],
                ];

                if (isset($currentExtraIds[$index])) {
                    // Update the existing extra
                    $extra = $currentExtras->find($currentExtraIds[$index]);
                    $extra->update($extraFields);
                } else {
                    // Create a new extra
                    $extra = new Extra($extraFields);
                    $product->extras()->save($extra);
                }
            }

            // Remove any remaining extras
            $extrasToRemove = array_slice($currentExtraIds, count($newExtras));
            Extra::whereIn('id', $extrasToRemove)->delete();
        }

        return back();
        // return Redirect::route('companies.products.index', ['company' => $company]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Product $product)
    {
        $this->authorize('manageProduct', [$product, $company]);

        //
    }
}
