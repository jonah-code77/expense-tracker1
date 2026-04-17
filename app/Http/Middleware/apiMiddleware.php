<?php
namespace App\Http\Middleware;


class ApiMiddleware implements MiddlewareInterface{
    
    public function handle() {
      
        header('Content-Type: application/json');
        
       
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
      
        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        return true;
    }
}