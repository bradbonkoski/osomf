<?php

//namespace osomf;

//require_once('lib/Template.php');
use \Template;

class ControllerBase
{

    protected $_controller;
    protected $_action;
    protected $_template;
    public $data = array();

    public function __construct($controller, $action = "") 
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    public function setAction($action) 
    { 
        $this->_action = $action;
    }

    protected function parseParams($params) 
    {
        //return explode('/', $params);
        $list = explode('/', $params);
        $ret = array();
        
        /*
            If they provide params with an '=' sign, 
            then make that associative, otherwise, just append to the array
        */
        foreach ($list as $l) {
            if (strstr($l, "=")) {
                list($k, $v) = explode("=", $l);
                $ret[$k] = $v;
            } else {
                $ret[] = $l;
            }
        }
        return $ret;
    }

    public function __destruct() 
    {
        $this->_template = new Template($this->_controller, $this->_action);
        foreach ($this->data as $k => $v) {
            $this->_template->set($k, $v);
        }
        $this->_template->render();    
    }
}    
