<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 9:00 AM
 * To change this template use File | Settings | File Templates.
 */

use \osomf\models\LocationModel;

class location extends ControllerBase
{

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("location", $action);

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
        $locId = $params[0];
        if (!is_numeric($locId)) {
            echo "ERROR";
        }

        $l = new LocationModel(LocationModel::RO);
        $l->fetchLocInfo($locId);
        //echo "User: $u\n";
        $this->data['title'] = "Location information for: ".$l->locName;
        $this->data['locName'] = $l->locName;
        $this->data['locDesc'] = $l->locDesc;
        //$this->data['projOwner'] = ;

    }
}
