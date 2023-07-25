<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Http\Requests\Auth\LoginRules;
use App\Http\Requests\Auth\SignupRules;
use App\Http\Services\UserService;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


/**
 * @group Authentication
 *
 * APIs for Authentication and user related informations
 */

class AuthController extends Controller
{
    private $service;

    public function __construct()
    {
        $model = new User();
        $this->service = new UserService($model);
    }
    public function signup(Request $request)
    {
        try {

            $validate = Validator::make($request->all(),SignupRules::rules());
            if ($validate->fails())
                return $this->error($this->BodyErrorMessage($validate->messages()->all()), HttpStatusCodes::UnprocessableEnttiy);

            $createduser = $this->service->signUp($request->all());

            if ($createduser)
                return $this->success($createduser, HttpStatusCodes::Created);

            return $this->error("User could not created internal server error", HttpStatusCodes::GeneralServeError);
        } catch (Exception $exception) {
            return $this->error("User could not created internal server error", HttpStatusCodes::GeneralServeError);
        }
    }

    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), LoginRules::rules());

        if ($validate->fails())
            return $this->error($this->BodyErrorMessage($validate->messages()->all()), HttpStatusCodes::UnprocessableEnttiy);

        $user = $this->service->signIn($request->all());

        if ( $user )
            return $this->success($user, HttpStatusCodes::Ok);

        return $this->error('The pasword or email you have entered is incorrect !', HttpStatusCodes::BadRequest);

    }

    public function getInfo()
    {
        $user = auth('api')->user();
        if($user !== null)
            return $this->success($user, HttpStatusCodes::Ok);
        return $this->error('Not authenticate', HttpStatusCodes::BadRequest);
    }
}
