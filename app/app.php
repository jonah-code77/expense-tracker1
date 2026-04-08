<?php

use App\Core\Session;

//BASE URL
DEFINE('BASE_URL', '/expense-tracker');
//BASE PATH 
DEFINE("BASE_PATH",__DIR__."/../");
//Autoload Files
spl_autoload_register(function($class){ 
    //paths
    $paths = [ 'app/config/', 'app/controller/', 'app/model/', 'app/core/', 'app/middleware/'];

    //files extentions
    $exts = ['.php', '.controller.php', '.model.php', '.core.php'];

    foreach($paths as $path){
        
        foreach($exts as $ext){
            $class = str_replace('\\', '/', $class);
            $fullPath = $path . strtolower($class) . $ext;
            if (file_exists($fullPath)) {
                try {
                    require_once $fullPath;
                    return;
                } catch (\Throwable $e) {
                    die("error loading class '$class' from file '$file':" . $e->getMessage());
                }
            }
        }
    }
    throw new Exception("Autoload Error: Class '{$class}' not found. Checked paths: " . implode(', ', $paths));
});

Session::start();