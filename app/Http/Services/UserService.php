<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model, ["name", "email", "password"]);
    }

}

