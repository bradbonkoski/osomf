<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/7/11
 * Time: 1:52 PM
 * To change this template use File | Settings | File Templates.
 */
 
namespace osomf\models;

use osomf\DB;
use \osomf\models\AssetModel;
use \osomf\models\ProjectModel;

class Impact extends DB
{

    private $_impactId;
    private $_impactType;
    private $_impactValue;
    private $_impactDesc;
    private $_impactSeverity;
    public $impactObj;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_impactId = -1;
        $this->_impactType = -1;
        $this->_impactValue = -1;
        $this->_impactDesc = '';
        $this->_impactSeverity = -1;
        $this->impactObj = NULL;
    }
}