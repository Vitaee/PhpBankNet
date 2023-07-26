<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Account;
use App\Models\User;

class AccountService extends BaseService
{
    protected $model;

    public function __construct(Account $model)
    {
        parent::__construct($model, ["balance", "user_id"]);
    }

    public function updateBalance(int $amount,  $user) {

        $account = $user->account;

        if ($amount > 0 && $amount <= 500) {
            $newBalance = $account->balance - $amount;

            if ($newBalance >= -500) {
                $account->update(['balance' => $newBalance]);
                return ['message' => 'Withdraw successful',  'status' => 200];
            }

            $overLimit = abs($newBalance + 500);
            $charge = $overLimit * 0.02;
            $newBalance -= $charge;

            if ($newBalance >= -500) {
                $account->update(['balance' => $newBalance]);
                return ['message' => 'Withdraw successful with 2% charge', 'status' => 200];
            }

            return ['message' => 'Withdraw not allowed. Insufficient funds', 'status' => 400];
        }

        return ['message' => 'Invalid withdraw amount', 'status' => 400];
    }

    public function deposit(int $amount,  $user) {

        if(empty($user->account)){
            $account = $this->model->create(['balance' => $amount, "user_id" => $user->id]);
        } else {
            $account = $user->account;
        }


        if ($amount > 0) {
            $newBalance = $account->balance + $amount;

            if ($newBalance <= -500) {
                $overLimit = abs($newBalance + 500);
                $charge = $overLimit * 0.02;
                $newBalance += $charge;

                $account->update(['balance' => $newBalance]);
                return ['message' => 'Deposit successful with 2% charge' , "status" => 200];
            }

            $account->update(['balance' => $newBalance]);
            return ['message' => 'Deposit successful',  "status" => 200];
        }

        return ['message' => 'Invalid deposit amount', "status" => 400];
    }

}

