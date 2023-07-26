<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class Deposit extends FormRequest
{

    static public function rules(): array
    {
        return [
            'amount' => 'required|integer|max:1000|min:1',
        ];
    }

}
