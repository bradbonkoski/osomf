<?php

use \osomf\models\CiStatus;

class status extends ControllerBase
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
        $s = new CiStatus(CiStatus::RO);

        if ($this->_test) {
            return json_encode($s->autocomplete("statusName", $str[1]));
        }
        // @codeCoverageIgnoreStart
        echo json_encode($s->autocomplete("statusName", $str[1]));
        // @codeCoverageIgnoreEnd
    }
}