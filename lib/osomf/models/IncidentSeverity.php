<?php

/**
 * Incident Severity Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */
 
namespace osomf\models;

use osomf\DB;

/**
 * Incident Severity Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

class IncidentSeverity extends DB
{

    private $_sevId;
    private $_sevName;
    private $_sevDesc;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_sevId = -1;
        $this->_sevName = '';
        $this->_sevDesc = '';

        $this->_table = "severity";
        $this->_tableKey = "sevId";
    }

    public function getSevId()
    {
        return $this->_sevId;
    }

    public function getSevName()
    {
        return $this->_sevName;
    }

    public function getSevDesc()
    {
        return $this->_sevDesc;
    }

    public function loadSeverity($sevId)
    {
        if (!is_numeric($sevId) || $sevId <= 0 ) {
            throw new \Exception("Bad Severity Id");
        }

        $sql = "select * from severity where sevId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($sevId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Severity id {$sevId} does not exist");
        }

        $this->_sevId = $sevId;
        $this->_sevName = $row['sevName'];
        $this->_sevDesc = $row['sevDesc'];
    }

    public function getAllSeverity()
    {
        $sql = "select sevId, sevName from severity";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $ret = array();
        foreach ($rows as $r) {
            $ret[$r['sevId']] = $r['sevName'];
        }
        return $ret;
    }
}