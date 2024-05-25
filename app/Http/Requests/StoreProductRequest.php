<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'product_category' => 'required', 'exists:product_categories,name',
            'image_caption' => ['required', 'exists:filament_media_library,caption'],
            'image_id' => ['required', 'exists:media,id'],
            'price' => ['required', 'decimal:2', 'max:999999'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
