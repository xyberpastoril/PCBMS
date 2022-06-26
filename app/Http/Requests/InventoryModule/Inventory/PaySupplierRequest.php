<?php

namespace App\Http\Requests\InventoryModule\Inventory;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaySupplierRequest extends FormRequest
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
            'consign_order' => ['required'],
            'products' => ['required', 'array'],
        ];
    }
}
