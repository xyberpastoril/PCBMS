<?php

namespace App\Http\Requests\InventoryModule\Supplier;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->designation == 'manager';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'physical_address' => ['required'],
            'mobile_number' => ['required'], // Add formatting later
            'email_address' => ['sometimes', 'email'],
        ];
    }
}
