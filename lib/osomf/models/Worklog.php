<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/10/11
 * Time: 7:16 AM
 * To change this template use File | Settings | File Templates.
 */
 
namespace osomf\models;
use osomf\DB;
use \osomf\models\IncidentModel;

use \osomf\worklog\Status;

class Worklog extends DB
{

    const TYPE_WORKLOG = 'WORKLOG';
    const TYPE_STATUS = 'STATUS';
    const TYPE_SOCIAL = 'SOCIAL';

    public $validTypes = array(
        self::TYPE_WORKLOG,
        self::TYPE_STATUS,
        self::TYPE_SOCIAL,
    );

    private $_worklogId;
    private $_incidentId;
    private $_userId;
    private $_mtime;
    private $_type;
    private $_data;
    private $_msgType;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
           throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

    }

    public function getIncidentId()
    {
        return $this->_incidentId;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getMtime()
    {
        return $this->_mtime;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getData()
    {
        return $this->_data;
    }


    private function _validateIncident($incidentId)
    {
        $i = new IncidentModel(self::RO);
        return $i->validateKey($incidentId);
    }

    private function _setMsgType()
    {
        switch($this->_type) {
            case self::TYPE_STATUS:
                $this->_msgType = new Status();
        }
    }

    public function getWlEntry($entryId)
    {
        $sql = "select * from worklog where worklogId = ?";
        $stmt = $this->_db->prepare($sql);

        if (!$stmt->execute(array($entryId))) {
            error_log(print_r($stmt->errorInfo(), true));
            throw new \Exception(
                "Error in fetching Worklog"
            );
        }
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("No Worlog Entry: {$entryId} found");
        }

        $this->_incidentId = $row['incidentId'];
        $this->_userId = $row['userId'];
        $this->_mtime = $row['mtime'];
        $this->_type = $row['wlType'];
        $this->_setMsgType();
        $this->_data = $this->_msgType->decodeEntry($row['data']);
    }

    public function newWorkLog($incidentId, $user, $type, $data)
    {
        if (!in_array($type, $this->validTypes)) {
            throw new \Exception(
                "Type {$type} is not a valid worklog entry type"
            );
        }

        if (!$this->_validateIncident($incidentId)) {
            throw new \Exception(
                "Incident id: {$incidentId} not known"
            );
        }

        $this->_type = $type;
        $this->_setMsgType();
        $this->_userId = $user;
        $this->_incidentId = $incidentId;
        $this->_data = $data;
    }

    public function save()
    {
        $sql = "insert into worklog set
            incidentId = ?,
            userId = ?,
            wlType = ?,
            data = ?";
        $stmt = $this->_db->prepare($sql);
        if (!$stmt->execute(
            array(
                $this->_incidentId,
                $this->_userId,
                $this->_type,
                $this->_msgType->encodeEntry($this->_data),
            )
        )) {
            error_log(print_r($stmt->errorInfo(), true));
            throw new \Exception(
                "Error: Unable to Write to Worklog"
            );
        }
    }

    public function getWorklogTypes()
    {
        return $this->validTypes;
    }
}