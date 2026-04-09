<?php

use App\Core\Session;

//BASE URL
DEFINE('BASE_URL', '/expense-tracker1');
//BASE PATH 
DEFINE("BASE_PATH",__DIR__."/../");
//Autoload Files
spl_autoload_register(function($class){

    if (strpos($class, 'App\\') !== 0) return;

    $path = str_replace('\\', '/', $class);
    $path = str_replace('App/', '', $path);
    $filePath = [];
    $exts = ['.php', '.controller.php', '.model.php', '.core.php'];
    foreach ($exts as $ext) {
        $file = BASE_PATH . 'app/' . strtolower($path) . $ext;
        $filePath[] = $file;
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    throw new \Exception("Autoload Error: Class '$class' not found. Tried: ". implode(', ', $filePath));
});

Session::start();