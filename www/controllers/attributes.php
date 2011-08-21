<?php

/**
 * Attribute Controller
 *
 *
 * @category    Controller
 * @package     Status
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\CiAttributes;

/**
 * Attribute Controller
 *
 *
 * @category    Controller
 * @package     Status
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class attributes extends ControllerBase
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
        $s = new CiAttributes();

        if ($this->_test) {
            return json_encode($s->autocomplete("attrName", $str[1]));
        }
        // @codeCoverageIgnoreStart
        echo json_encode($s->autocomplete("attrName", $str[1]));
        // @codeCoverageIgnoreEnd
    }
}