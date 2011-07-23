<?php

/**
 * Type (CI) Controller
 *
 *
 * @category    Controller
 * @package     Type
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\CiType;

/**
 * Type (CI) Controller
 *
 *
 * @category    Controller
 * @package     Type
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

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

        if ($this->_test) {
            return json_encode($s->autocomplete("typeName", $str[1]));
        }

        // @codeCoverageIgnoreStart
        echo json_encode($s->autocomplete("typeName", $str[1]));
        // @codeCoverageIgnoreEnd
    }
}