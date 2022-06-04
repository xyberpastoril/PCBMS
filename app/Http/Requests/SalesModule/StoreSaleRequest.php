<?php

namespace App\Http\Requests\SalesModule;

use App\Actions\DecodeTagifyField;
use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->designation == 'cashier';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer' => ['sometimes'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'products' => ['required', 'array'],
            'products.*' => ['sometimes'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'numeric'],
        ];
    }

    protected function prepareForValidation()
    {        
        $products = [];
        $quantities = [];

        // exit(var_dump($this->products));

        for($i = 0; $i < count($this->products); $i++) {
            if($this->products[$i] != null) {
                $products[] = DecodeTagifyField::run($this->products[$i]);
            }
        }

        if(isset($this->quantities)) {
            for($i = 0; $i < count($this->quantities); $i++) {
                $quantities[] = intval($this->quantities[$i]);
            }
        }

        $this->merge([
            'customer' => isset($this->customer) ? [DecodeTagifyField::run($this->customer)] : null,
            'products' => $products,
            'quantities' => $quantities,
        ]);
    }
}
