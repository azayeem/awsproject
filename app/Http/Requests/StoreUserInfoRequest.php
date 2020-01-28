<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserInfoRequest extends FormRequest
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
            'userinfo' => ['required', 'file'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'birth_date' => ['required', 'date_format:d/m/Y']
        ];
    }
}
