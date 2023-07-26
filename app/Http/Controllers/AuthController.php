<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Http\Requests\Auth\LoginRules;
use App\Http\Requests\Auth\SignupRules;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


/**
 * @group Authentication
 *
 * APIs for Authentication and user related informations
 */

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        try {

            $validate = Validator::make($request->all(),SignupRules::rules());

            if ($validate->fails())
                return $this->error($this->BodyErrorMessage($validate->messages()->all()), HttpStatusCodes::UnprocessableEnttiy);

            $user = $request->all();
            $user['password'] = bcrypt($user['password']);
            $user['remember_token'] = Str::random(10);


            $createduser = User::create($user);

            if ($createduser)
                return $this->success($createduser, HttpStatusCodes::Accepted);

            return $this->error("User could not created internal server error", HttpStatusCodes::GeneralServeError);
        } catch (Exception $exception) {
            return $this->error("User could not created internal server error", HttpStatusCodes::GeneralServeError);
        }
    }


    public function getInfo()
    {
        $user = auth('api')->user();
        if($user !== null)
            return $this->success($user, HttpStatusCodes::Ok);
        return $this->error('Not authenticate', HttpStatusCodes::BadRequest);
    }

    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), LoginRules::rules());

        if ($validate->fails())
            return $this->error($this->BodyErrorMessage($validate->messages()->all()), HttpStatusCodes::UnprocessableEnttiy);

        $user = User::where('email', $request->email)->first();


        if ($user) {

            if (!auth()->attempt($request->all())) {
                return $this->error('The email or password you have entered is incorrect !', HttpStatusCodes::BadRequest);
            }

            $tokenres = $user->createToken('Personal Access Token')->accessToken;

            $data = array(
                    "id" => $user->id,
                    "email" => $user->email,
                    "access_token" => $tokenres,
            );

            return $this->success($data, HttpStatusCodes::Ok);
        }

        return $this->error('The email you have entered is incorrect !', HttpStatusCodes::BadRequest);
    }
}
