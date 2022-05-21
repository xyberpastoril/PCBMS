<?php

namespace App\Http\Requests\InventoryModule\Inventory;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Foundation\Http\FormRequest;

class ReceiveProductsRequest extends FormRequest
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
            'supplier' => ['required'],
            'date' => ['date', 'before_or_equal:today'],
            'products' => ['required', 'array'],
            'products.*' => ['required'],
            'particulars' => ['required', 'array'],
            'particulars.*' => ['required'],
            'expiration_dates' => ['required', 'array'],
            'expiration_dates.*' => ['required', 'after:today'],
            'unit_prices' => ['required', 'array'],
            'unit_prices.*' => ['required', 'numeric', 'min:0'],
            'sale_prices' => ['required', 'array'],
            'sale_prices.*' => ['required', 'numeric', 'min:0'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'numeric', 'min:1'],
        ];
    }
}
