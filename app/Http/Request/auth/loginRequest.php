<?php
namespace App\Http\Request\Auth;

use App\Http\Request\BaseRequest;

class LoginRequest extends BaseRequest{
    public function rules(){
        return [
            "nameOrEmail"=> "required",
            "password"=> "required"
        ];
    }

}