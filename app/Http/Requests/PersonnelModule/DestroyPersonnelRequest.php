<?php

namespace App\Http\Requests\PersonnelModule;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyPersonnelRequest extends FormRequest
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
            'password' => ['required', 'current_password'],
        ];
    }
}
