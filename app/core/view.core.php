<?php

class View {

    protected static $sections = [];
    protected static $currentSection;
    protected static $currentPage;
    protected static $shared = [];
   
    //link our controller to our view files(layout if neccessary)
    public static function views($url,$data = [], $layoutOverride = null){
       self::$sections = [];

        $file = __DIR__ . ("/../view/$url.view.php");

        $paths = explode('/', $url);
        $folder = $paths[0];
        $page = $paths[1] ?? 'index';
        self::$currentPage = $page;  

        if (file_exists($file)) {
            $data = array_merge(self::$shared, $data);
            extract($data);
            $layoutFile = __DIR__ . ("/../view/$folder/layout.view.php");

            if ($layoutOverride === false) {
                require $file;
                return;
            }

            if ($layoutOverride !== null) {
                $layoutFile = __DIR__ . "/../view/layouts/$layoutOverride.view.php";
            }

            ob_start();
            
            require $file;

            $content = ob_get_clean();

            if (file_exists($layoutFile)) {
                $data['currentPage'] = self::$currentPage;
                extract($data);
                require $layoutFile;

            }else{
                die("Layout not found for $folder");
            }
            
        }else{
            die("file not found: $url");
        }
    }

    //shared layout
    public static function share($key, $value){
        self::$shared[$key] = $value;
    }
    
    //Start Section
    public static function section($name){
        self::$currentSection = $name;
        ob_start();
    }

    //EndSection
    public static function endSection(){
        self::$sections[self::$currentSection] = ob_get_clean();
    }

    //Render a section 
    public static function yield($name){
        echo self::$sections[$name] ?? "";
    }

    public static function getCurrentPage(){
        return self::$currentPage ?? '';
    }
}



