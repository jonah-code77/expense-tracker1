<?php
namespace App\Http\Request\Auth;
use App\Http\Request\BaseRequest;

class RegisterRequest extends BaseRequest{
    public function rules(){
        return [
            'name' => 'required:Name is required',
            'email' => 'required|email',
            'password' => 'required'
        ];
    }
}