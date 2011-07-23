<?php

/**
 * Home Controller
 *
 *
 * @category    Controller
 * @package     Home
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\IncidentModel;

/**
 * Home Controller
 *
 *
 * @category    Controller
 * @package     Home
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class home extends ControllerBase
{
    public function __construct($controller = '', $action = '')
    {
        parent::__construct("home", $action);
    }

    public function view($params)
    {
        $this->setAction("view");
        $params = $this->parseParams($params);
        $this->data['pageTitle'] = 'OSOMF - Home Page/Dashboard';
        $i = new IncidentModel();
        $this->data['incidents'] = $i->listHomeIncidents();
    }

}