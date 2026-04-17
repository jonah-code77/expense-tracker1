<?php

namespace App\Http\Request;

use App\Core\Validator;

abstract class BaseRequest{
    protected $data;
    protected $errors = [];

    public function __construct( $data){
        $this->data = $data;
    }

    abstract public function rules();

    public function validate(){
        if ($this->allEmpty()) {
            $this->errors = [
                '*' => 'Please fill all inputs'
            ];
            return false;
        }

        $this->errors = Validator::make($this->data, $this->rules());

        return empty($this->errors);
    }

    private function allEmpty() {
        foreach ($this->data as $value) {
            if (!empty(trim($value ?? ''))) {
                return false;
            }
        }
        return true;
    }

    public function errors(){
        return $this->errors;
    }

    public function data(){
        return $this->data;
    }
}