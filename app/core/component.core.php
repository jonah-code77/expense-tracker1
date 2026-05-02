<?php
namespace App\Core;

class Component{
    //components
    public static function Render($name, $data = []){
       
        $file =  __DIR__ . ("/../view/components/$name.view.php");
        extract($data);
        if (file_exists($file)){
            
            require $file;
        } else {
            throw new \RuntimeException("Component [{$name}] not found.");
        }
    }
}