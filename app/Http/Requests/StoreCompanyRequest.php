<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
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
            'address' => ['string', 'unique:companies,address', 'max:255'],
            'company_type_id' => ['required', 'exists:company_types,id'],
            'company_categories' => ['required', 'exists:company_categories,id', 'array'],
            'company_fulfillment_types' => ['exists:fulfillment_types,id', 'array'],
            'logo' => ['file', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
