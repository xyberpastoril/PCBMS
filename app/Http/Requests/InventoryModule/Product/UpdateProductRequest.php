<?php

namespace App\Http\Requests\InventoryModule\Product;

use App\Actions\DecodeTagifyField;
use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
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
            'unit' => ['required'],
            'expiry_duration' => ['required', 'numeric', 'min:1'],
            'expiry_duration_type' => ['required', 'in:day,week,month,year'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'unit' => DecodeTagifyField::run([$this->unit]),
        ]);
    }
}
