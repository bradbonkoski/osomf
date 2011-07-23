<?php

/**
 * Project Controller
 *
 *
 * @category    Controller
 * @package     Project
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\ProjectModel;

/**
 * Project Controller
 *
 *
 * @category    Controller
 * @package     Project
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class project extends ControllerBase
{

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("project", $action);

    }

    public function view( $params )
    {
        $this->setAction("view");
        //echo "Params are: $params\n";
        $parms = $this->parseParams($params);
        if (array_key_exists("format", $parms)) {
            if ($parms['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }
        $projId = $params[0];
        if (!is_numeric($projId)) {
            echo "ERROR";
        }

        $p = new ProjectModel(ProjectModel::RO);
        $p->fetchProjInfo($projId);
        //echo "User: $u\n";
        $this->data['title'] = "Project Data for: ".$p->projName;
        $this->data['projName'] = $p->projName;
        $this->data['projDesc'] = $p->projDesc;
        //$this->data['projOwner'] = ;

    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        //error_log("Query String is: {$str[1]}");
        $p = new ProjectModel(ProjectModel::RO);

        if ($this->_test) {
            return json_encode($p->autocomplete("projName", $str[1]));
        }

        // @codeCoverageIgnoreStart
        echo json_encode($p->autocomplete("projName", $str[1]));
        // @codeCoverageIgnoreEnd
    }
}
