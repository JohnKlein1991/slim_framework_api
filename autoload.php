<?php
function autoloader($className)
{
    $paths = [
        '/models/',
        '/components/',
        '/controllers/'
    ];
    foreach ($paths as $path){
        $path = ROOT . $path . $className.'.php';
        if(is_file($path)){
            require_once $path;
        }
    }
}
spl_autoload_register('autoloader');