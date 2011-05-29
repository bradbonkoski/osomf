<?php

define('BASE_PATH', dirname(dirname(__FILE__)));

spl_autoload_register(
   function($class) {
        $file = BASE_PATH . "/lib/".strtr($class, '\\', '/') . '.php';
        //echo "File is: $file\n";
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }
);


//echo "Base Path is: ".BASE_PATH."\n";
require('lib/RoutingLib.php');
require('lib/ControllerBase.php');


