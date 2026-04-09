<?php
namespace App\Core;
class Env{
    public static function load($key){
        if(!file_exists($key)) throw new \Exception(".env file not found at $key");

        $lines = file($key, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line){
            $line = trim($line);

            if($line === "" || str_starts_with($line, '#')){
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $value = trim($value);
            $key = trim($key);

            $_ENV[$key] = $value;
        }
    }

    // optional helper
    public static function get($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}