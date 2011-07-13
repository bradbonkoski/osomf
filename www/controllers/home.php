<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/21/11
 * Time: 2:33 PM
 * To change this template use File | Settings | File Templates.
 */

use \osomf\models\IncidentModel;

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