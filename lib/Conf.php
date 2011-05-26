<?php


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
        $configPath = PATH."/conf/";
        $this->_data = parse_ini_file($configPath.$file, true);
    }
}


class ConfDb extends Conf
{

    public function __construct() 
    {
        parent::__construct("db.ini", self::CONFIG_DB);
    }


    public function dumpAllChange() 
    {
        print_r($this->_data['change']); 
    }

    public function dumpAll() 
    {
        print_r($this->_data); 
    }

    public function getAllChange() 
    {
        return $this->_data['change'];
    }

    public function getAllUser()
    {
        return $this->_data['omf_users'];
    }
    
    public function getChangeValue($key) 
    {
        return $this->_data['change'][$key];
    }    
    
}

class ConfApp extends Conf
{

}
