<?php
namespace App\Middleware;
use App\Core\Session;
class AdminMiddleware implements MiddlewareInterface {
    public function handle() {
        if (!Session::hasRole('admin')) {
           echo json_encode([
                'status' => 'error',
                'message' => 'Forbidden: Admin access required'                
            ]);
            exit;
        }
    }
}

