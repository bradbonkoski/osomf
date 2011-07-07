<?php

//echo "<pre>".print_r($_SERVER, true)."</pre>";

// Set up our Default include path
$path = dirname(dirname(__FILE__));
define('PATH', $path);
//echo "Path is: $path<br/>";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

//include the bootstrap, the entry point for all that is holy
require('lib/bootstrap.php');

//construct our route
$route = new Routes($_SERVER['REQUEST_URI']);
//var_dump($route);

//debug
//echo "Controller is: {$route->getController()}<br/>";
//echo "Action is: {$route->getAction()}<br/>";
//echo "Params are: ".print_r($route->getParams(), true)."<br/>";

//Load the controller class and get the party started!

$cont = $route->getController();
if ($route->isWS) {
    $prefix = "ws/";
} else {
    $prefix = "www/controllers/";
}

if (file_exists(PATH."/{$prefix}{$cont}.php")) {
    require("{$prefix}{$cont}.php");
    $controller = new $cont();

    if ((int)method_exists($controller, $route->getAction())) {
        call_user_func_array(
            array($controller, $route->getAction()), 
            array($route->getParams())
        );
    } else {
        //some error handling here
        echo "ERROR!<br/>";
    }
} else {
    echo "PATH: ".PATH."{$prefix}{$cont}.php\n";
    //default to main controller
    require("www/controllers/main.php");
    $controller = new main("main", "index");
    call_user_func_array(array($controller, "index"), array());
}
