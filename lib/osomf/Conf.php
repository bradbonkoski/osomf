<?php

namespace osomf;

if (!defined('PATH')) {
    define('PATH', dirname(dirname(__FILE__)));
}

class Conf
{

    const CONFIG_DB = 'db';
    const CONFIG_APP = 'app';

    protected $_data;

    public function __construct($file, $type) 
    {
        if ($type != self::CONFIG_DB && $type != self::CONFIG_APP) {
            throw new Exception(
                "Invalid Type: $type ".__FILE__." : ".__LINE__
            );
        }
        //echo "Path is: ".PATH."\n";
        $configPath = PATH."/../conf/";
        $this->_data = parse_ini_file($configPath.$file, true);
    }
}




class ConfApp extends Conf
{

}
