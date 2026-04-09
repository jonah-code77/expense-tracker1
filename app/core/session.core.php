<?php

namespace App\Core;

class Session {

    //check if session is started already, if not it start a new session;
    public static function start(){
        if (session_status() === PHP_SESSION_NONE) {
            session_name('final');
            session_start();
        }
    } 

    //Gets the key of a session after which it set it equal to the value
    public static function setSession($key,$value){
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function getSession($key){
        self::start();
        return $_SESSION[$key] ?? null;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function getAll(){
        self::start();
        return $_SESSION;
    }

    public static function flash($key, $value){
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash($key){
        self::start();

        if(isset($_SESSION['_flash'][$key])){
            $message = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $message;
        }

        return null;
    }

    public static function destroy(){
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    public static function removeSession($key){
        unset($_SESSION[$key]);
    }

    public static function isLoggedIn() {
        return self::has('user_id') || self::has('email');
    }

    public static function user() {
        return [
            'id' => self::getSession('user_id'),
            'email' => self::getSession('email'),
            'role' => self::getSession('role'),
            'name' => self::getSession('name')
        ];
    }

    public static function role() {
        return strtolower(self::user()['role'] ?? '');
    }

    
    public static function hasRole($roles) {
        $userRole = self::role();

        if (!$userRole) return false;

        if (is_array($roles)) {
            return in_array($userRole, array_map('strtolower', $roles));
        }

        return $userRole === strtolower($roles);
    }

}