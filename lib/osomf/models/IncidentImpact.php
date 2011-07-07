<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/7/11
 * Time: 1:51 PM
 * To change this template use File | Settings | File Templates.
 */
 
namespace osomf\models;

use osomf\DB;
use \osomf\models\Impact;

class IncidentImpact extends DB
{

    const IMPACT_CI = 'asset';
    const IMPACT_PROJ = 'project';

    public $validImpacts = array(
        self::IMPACT_CI,
        self::IMPACT_PROJ,
    );

    private $_incidentId;
    private $_impacts;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_incidentId = -1;
        $this->_impacts = array();
    }

    public function getIncidentId()
    {
        return $this->_incidentId;
    }


}