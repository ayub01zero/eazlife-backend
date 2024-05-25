<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadInventoryRequest extends FormRequest
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
            'inventory_file' => 'required|file|mimes:pdf|max:2048', // Only allow PDF files up to 2MB
        ];
    }
}
