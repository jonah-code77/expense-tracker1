<?php

namespace App\Http\Request;

class TransactionRequest extends BaseRequest{
    public function rules()
    {
        return [
            'type' => 'required',
            'amount' => 'required',
            'category' => 'required',
        ];
    }
}