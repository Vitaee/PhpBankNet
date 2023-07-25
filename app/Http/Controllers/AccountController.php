<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Http\Requests\Account\Withdraw;
use App\Http\Services\AccountService;
use App\Http\Services\UserService;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    private $service;

    public function __construct()
    {
        $model = new Account();
        $this->service = new AccountService($model);
    }

    public function withdraw(Request $request)
    {
        $validate = Validator::make($request->all(), Withdraw::rules());

        if ($validate->fails())
            return $this->error($this->BodyErrorMessage($validate->messages()->all()), HttpStatusCodes::UnprocessableEnttiy);

        $data = $this->service->updateBalance($request->input("amount"), $request->user());

        return $this->success([$data["message"]],  $data["status"]);

    }

    public function deposit(Request $request)
    {
        $validate = Validator::make($request->all(), Withdraw::rules());

        if ($validate->fails())
            return $this->error($this->BodyErrorMessage($validate->messages()->all()), HttpStatusCodes::UnprocessableEnttiy);

        $data = $this->service->deposit($request->input("amount"), $request->user());

        return $this->success([$data["message"]],  $data["status"]);

    }

    public function balance(Request $request): JsonResponse
    {
        return response()->json(['balance' => $request->user()->account->balance], 200);
    }
}
