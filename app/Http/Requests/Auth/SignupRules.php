<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignupRules extends FormRequest
{

    static public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'email|required|unique:users',
            'password' => 'required|string',
            'confirmation_password' => 'required|string'
        ];
    }

}
