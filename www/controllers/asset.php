<?php

class asset extends ControllerBase
{
    public function __construct($controller = '', $action = '') 
    { 
        parent::__construct("asset", $action);
    }

    public function add( $params )
    { 
        $this->setAction("add");
        $parms = $this->parseParams($params);
    }
}
