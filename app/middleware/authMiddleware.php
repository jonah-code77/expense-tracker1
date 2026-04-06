<?php
class AuthMiddleware implements MiddlewareInterface {
    public function handle() {
        if (!Session::isLoggedIn()) {
            header("Location:" . BASE_URL . "/login");
            exit;
        }
        return true;
    }
}