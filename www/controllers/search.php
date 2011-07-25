<?php

/**
 * Search Controller
 *
 *
 * @category    Controller
 * @package     Search
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\IncidentModel;
use \osomf\models\AssetModel;

/**
 * Search Controller
 *
 *
 * @category    Controller
 * @package     Search
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */
 
class search extends ControllerBase
{
    public function __construct($controller = '', $action = '')
    {
        parent::__construct("search", $action);
    }

//    public function search()
//    {
//        return $this->incident();
//    }

    public function incident($params = '')
    {
        $this->data['results'] = -1;
        $this->setAction("incident");
        if (isset($_POST['btnSearch'])) {
            //print_r($_POST);
            $cols = array('incidentId','title','impact');
            $crit = array();
            $crit['title'] = $_POST['title'];
            $i = new IncidentModel();
            $res = $i->search($cols, $crit);
            //echo "<pre>".print_r($res, true)."</pre>";
            $this->data['results'] = count($res);
            $this->data['searchResults'] = $res;
            $this->data['cols'] = $cols;

        }
    }

    public function asset($params = '')
    {
        $this->data['results'] = -1;
        $this->setAction('asset');
        if (isset($_POST['btnSearch'])) {
            //print_r($_POST);
            $cols = array('ciid','ciName','ciDesc');
            $crit = array();
            $crit['ciName'] = $_POST['ciName'];
            $a = new AssetModel();
            $res = $a->search($cols, $crit);
            //echo "<pre>".print_r($res, true)."</pre>";
            $this->data['results'] = count($res);
            $this->data['searchResults'] = $res;
            $this->data['cols'] = $cols;
        }
    }

    public function quick($params)
    {
        $this->ac = true;
        $params = $this->parseGetParams($params);
        //echo "<pre>".print_r($params, true)."</pre>";
        list($cont, $id) = explode(":", urldecode($params['topSearchBox']));
        //echo "Controller: $cont and ID: $id<br/>";
        $this->redirect($cont, 'view', $id);
    }
}