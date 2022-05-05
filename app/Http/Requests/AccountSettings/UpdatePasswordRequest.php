<?php

namespace App\Http\Requests\AccountSettings;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdatePasswordRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => ['required', 'current_password'],
            'new_password' => ['required'],
            'confirm_new_password' => ['required', 'same:new_password'],
        ];
    }
}
