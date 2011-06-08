<?php

//namespace osomf;

//require_once('lib/Template.php');
use \Template;

class ControllerBase
{

    protected $_test;
    protected $_controller;
    protected $_action;
    protected $_template;
    public $data = array();

    public function __construct($controller, $action = "") 
    {
        $this->_test = false;
        $this->_controller = $controller;
        $this->_action = $action;
    }

    public function setTest()
    {
        $this->_test = true;
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
        // need to short circuit auto redirect to the view
        // in the instance where we are unit testing controllers
        if (!$this->_test) {
            $this->_template = new Template($this->_controller, $this->_action);
            foreach ($this->data as $k => $v) {
                $this->_template->set($k, $v);
            }
            $this->_template->render();
        }
    }
}    
