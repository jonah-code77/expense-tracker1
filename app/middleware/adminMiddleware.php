<?php

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

