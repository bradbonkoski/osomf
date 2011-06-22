<?php

class asset extends ControllerBase
{
    public function __construct($controller = '', $action = '') 
    { 
        parent::__construct("asset", $action);
    }

    public function view($params)
    {
        $this->setAction("view");
        $params = $this->parseParams($params);
    }

    public function add( $params )
    { 
        $this->setAction("add");
        $params = $this->parseParams($params);
    }
}
