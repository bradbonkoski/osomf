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
    private $_locId;
    private $_postData;

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("location", $action);

    }

    private function _loadLocInfo()
    {
        $l = new LocationModel(LocationModel::RO);
        $l->fetchLocInfo($this->_locId);
        //echo "User: $u\n";
        $this->data['title'] = "Location information for: ".$l->getLocName();
        $this->data['locName'] = $l->getLocName();
        $this->data['locDesc'] = $l->getLocDesc();
        $this->data['locAddr'] = $l->getLocAddr();

        $this->data['locId'] = $this->_locId;
        $this->data['changes'] = $l->getChanges();

        //echo "<pre>".print_r($this->data, true)."</pre>";
    }

    public function view( $params )
    {
        $this->setAction("view");
        //echo "Params are: $params\n";
        $params = $this->parseParams($params);
        if (array_key_exists("format", $params)) {
            if ($params['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }
        $locId = $params[0];
        if (!is_numeric($locId)) {
            echo "ERROR";
        }
        $this->_locId = $locId;
        $this->_loadLocInfo();

    }

    private function _saveLocInfo()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo($this->_locId);
        $l->setLocName($this->_postData['locName']);
        $l->setLocDesc($this->_postData['locDesc']);
        $l->setLocAddr($this->_postData['locAddr']);
        $l->save();
    }

    public function edit( $params )
    {
        $params = $this->parseParams($params);

        $locId = $params[0];
        $this->_locId = $locId;
        
        if (isset($_POST['locUpdate'])) {
            //echo "<pre>".print_r($_POST, true)."</pre>";
            $this->_postData = $_POST;
            $this->_saveLocInfo();
        }
        $this->setAction("edit");

        if (!is_numeric($locId)) {
            //todo fix this!
            echo "ERROR";
        }

        $this->_locId = $locId;
        $this->_loadLocInfo();
    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        //error_log("Query String is: {$str[1]}");
        $l = new LocationModel(LocationModel::RO);

        if ($this->_test) {
            return json_encode($l->autocomplete("locName", $str[1]));
        }

        // @codeCoverageIgnoreStart
        echo json_encode($l->autocomplete("locName", $str[1]));
        // @codeCoverageIgnoreEnd
    }

}
