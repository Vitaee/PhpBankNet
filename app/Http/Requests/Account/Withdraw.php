<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class Withdraw extends FormRequest
{

    static public function rules(): array
    {
        return [
            'amount' => 'required|integer|max:500|min:10',
        ];
    }

}
