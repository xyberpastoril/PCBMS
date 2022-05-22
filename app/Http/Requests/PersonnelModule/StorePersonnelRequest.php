<?php

namespace App\Http\Requests\PersonnelModule;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePersonnelRequest extends FormRequest
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
            'username' => ['required'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
            'designation' => ['required'],
        ];
    }
}
