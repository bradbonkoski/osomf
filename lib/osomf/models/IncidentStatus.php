<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/7/11
 * Time: 12:31 PM
 * To change this template use File | Settings | File Templates.
 */
 
namespace osomf\models;

use osomf\DB;

class IncidentStatus extends DB
{

    private $_statusId;
    private $_statusName;
    private $_statusDesc;
    private $_statusOrder;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_incident", $conn);

        $this->_statusId = -1;
        $this->_statusName = '';
        $this->_statusDesc = '';
        $this->_statusOrder = -1;

        $this->_table = "status";
        $this->_tableKey = "statusId";
    }

    public function getStatusId()
    {
        return $this->_statusId;
    }

    public function getStatusName()
    {
        return $this->_statusName;
    }

    public function getStatusDesc()
    {
        return $this->_statusDesc;
    }

    public function getStatusOrder()
    {
        return $this->_statusOrder;
    }

    public function loadStatus($statId)
    {
        if (!is_numeric($statId) || $statId <= 0 ) {
            throw new \Exception("Bad Status Id");
        }

        $sql = "select * from status where statusId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($statId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Status id {$statId} does not exist");
        }

        $this->_statusId = $statId;
        $this->_statusName = $row['statusName'];
        $this->_statusDesc = $row['statusDesc'];
        $this->_statusOrder = $row['orderNum'];
    }

    public function getAllStatus()
    {
        $sql = "select statusId, statusName from status order by orderNum asc";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $ret = array();
        foreach ($rows as $r) {
            $ret[$r['statusId']] = $r['statusName'];
        }
        return $ret;
    }
}