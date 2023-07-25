<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{

    protected function success($data, $code)
    {

        return response($data, $code);
    }

    protected function error($data, $code)
    {
        return response(["error" => $data], $code);
    }


    protected function BodyErrorMessage($errors)
    {
        $error_msg = "";
        foreach ($errors as $msg) {
            $error_msg .= $msg;
        }
        return $error_msg;
    }

    protected function ErrorMessageBuilder($keys, $errors) {
        $messages=  [];
        foreach($keys as $key) {
            $messages[$key] = $errors->get($key);
        }
        return $messages;
    }
}
