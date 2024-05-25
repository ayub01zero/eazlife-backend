<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class ProductController extends Controller
{
    public function getProductExtras(Request $request)
    {
        $this->validate($request, ['id' => 'exists:products,id|required|numeric']);

        $product = Product::with(['extras' => function ($query) {
            $query->where('is_approved', true);
        }])->where('is_approved', true)->find($request->input('id'));

        return response()->json($product);
    }
}
