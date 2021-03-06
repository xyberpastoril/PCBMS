<?php

namespace App\Http\Requests\InventoryModule\Unit;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUnitRequest extends FormRequest
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
            'abbreviation' => ['required'],
        ];
    }
}
