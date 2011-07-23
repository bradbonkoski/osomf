<?php

/**
 * Remediation Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

namespace osomf\models;
 
use osomf\DB;
use osomf\Validator;

/**
 * Remediation Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class RemediationModel extends DB
{
    private $_remId;
    private $_incidentId;
    private $_ownerId;
    public $owner;
    private $_ctime;
    private $_mtime;
    private $_notes;
    private $_rootCauseCat;
    private $_rootCauseDesc;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_remId = -1;
        $this->_incidentId = -1;
        $this->_ownerId = -1;
        $this->owner = null;
        $this->_notes = '';
        $this->_rootCauseCat = -1;
        $this->_rootCauseDesc = '';
    }
}