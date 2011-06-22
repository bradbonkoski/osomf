<?php
    
class incident extends ControllerBase
{
    public function __construct($controller = '', $action = '') 
    { 
        parent::__construct("incident", $action);
    }

    public function view( $params ) 
    {
        $this->setAction("view");
        $parms = $this->parseParams($params);
        //print_r($parms);
    }
}
