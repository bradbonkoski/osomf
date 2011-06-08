<?php

namespace osomf;

class Template
{

    protected $_variables = array();
    private $_contoller;
    private $_action;

    function __construct($controller, $action) 
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    
    public function render() 
    {

        extract($this->_variables);
    
        if (file_exists(PATH."/www/views/header.phtml")) {
            include(PATH."/www/views/header.phtml");
        }

        include("www/views/{$this->_controller}/{$this->_action}.phtml");

        if (file_exists(PATH."/www/views/footer.phtml")) {
            include(PATH."/www/views/footer.phtml");
        }
    }

    public function set($name, $value) 
    {
        $this->_variables[$name] = $value;
    }
}
