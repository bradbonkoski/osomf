<?php

error_reporting(-1);

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

use \osomf\ControllerBase;


