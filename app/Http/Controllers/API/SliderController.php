<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RalphJSmit\Filament\MediaLibrary\Media\Models\MediaLibraryItem;

class SliderController extends Controller
{
    public function getSlider(Request $request)
    {
        $this->validate($request, ['id' => 'exists:sliders,id|required|numeric']);

        $slider = Slider::with('slides')->find($request->input('id'));

        if (!$slider) {
            
            return response()->json(['message' => 'Slider not found'], 404);
        }

        $slider->slides->each(function ($slide) {
            $slide->image_path = env('MEDIA_URL') . $slide->image_path;

            if ($slide->product_id) {
                $slide->product = $slide->product;
                $slide->pickupTime = Company::find($slide->product->company_id)->calculatePickupTime();
            }
        });

        return response()->json($slider);
    }
}
