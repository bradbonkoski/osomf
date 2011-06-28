<?php

use \osomf\models\CiType;

class type extends ControllerBase
{
    public function __construct($controller = '', $action = '')
    {
        parent::__construct("asset", $action);
    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        //error_log("Query String is: {$str[1]}");
        $s = new CiType(CiType::RO);
        echo json_encode($s->autocomplete("typeName", $str[1]));
    }
}