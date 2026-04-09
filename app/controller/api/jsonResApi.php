<?php
namespace App\Controller\Api;
class JsonResApi{
    public static function Response($data, $statusCode = 200){
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;        
    }
}