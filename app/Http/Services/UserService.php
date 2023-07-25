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

    public function signUp(array $data): bool|User
    {
        $data['password'] = Hash::make($data['password']);
        $data['remember_token'] = Str::random(10);


        if ( User::where('email', $data["email"])->first()) {
            return false;
        }

        return $this->model->create($data);
    }

    public function signIn(array $data): bool|array
    {
        $user = User::where('email', $data["email"])->first();

        if ($user) {
            if (Hash::check($data['password'], $user->password)) {
                $tokenres = $user->createToken('Personal Access Token')->accessToken;
                $data = array(
                    "id" => $user->id,
                    "email" => $user->email,
                    "access_token" => $tokenres,
                );

                return $data;
            }

            return false;
        }

        return false;

    }

}

