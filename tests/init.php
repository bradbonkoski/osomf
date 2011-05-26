<?php

/**
* Testing bootstrap
*
* @category Test
* @package Test
* @author Brad Bonkoski <brad.bonkoski@yahoo.com>
* @copyright Copyright (c) 2011 
*/

// Set up our Default include path
$path = dirname(dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$logfile = dirname(__FILE__) . '/phpunit.log';
system('cat /dev/null > ' . $logfile);
ini_set('error_log', $logfile);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

