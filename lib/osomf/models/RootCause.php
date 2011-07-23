<?php

/**
 * RootCause Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

namespace osomf\models;
 
use osomf\DB;

/**
 * RootCause Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class RootCause extends DB
{
    const RC_CATEGORY = 'category';
    const RC_ROOTCAUSE = 'rootcause';
    const RC_REMEDIATION = 'remediation';
    
    private $_causeId;
    private $_causeType;
    private $_name;
    private $_desc;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_causeId = -1;
        $this->_causeType = '';
        $this->_name = '';
        $this->_desc = '';

        $this->_table = "rootCauses";
        $this->_tableKey = "causeId";
    }

    public function getCauseType()
    {
        return $this->_causeType;
    }

    public function getCauseName()
    {
        return $this->_name;
    }

    public function getCauseDesc()
    {
        return $this->_desc;
    }

    public function loadRootCause($causeId)
    {
        if (!is_numeric($causeId)) {
            throw new \Exception(
                "Root Cause Id Needs to be a number"
            );
        }
        
        $sql = "select * from rootCauses where causeId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($causeId));
        $row = $stmt->fetch();

        if ($row === false) {
            throw new \Exception("No Root Cause for id: {$causeId} found");
        }

        $this->_causeId = $causeId;
        $this->_causeType = $row['causeType'];
        $this->_name = $row['name'];
        $this->_desc = $row['description'];
    }
}