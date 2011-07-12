<?php
namespace osomf\models;

use osomf\DB;
use osomf\Validator;
use osomf\models\User;
use osomf\models\UserGroup;

/**
* Location Model Class
*
* @category Model
* @package Location
* @author Brad Bonkoski <brad.bonkoski@yahoo.com>
* @copyright Copyright (c) 2011
*/

class LocationModel extends DB
{
    private $_locId;
    private $_locName;
    private $_locDesc;
    private $_ownerType;
    public $locOwner; //maps to the UserModel Class
    private $_locOwnerId;
    private $_locAddr;

    private $_history;
    private $_changes;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_locId = -1;
        $this->_locName = '';
        $this->_locDesc = '';
        $this->_ownerType = self::OWNER_USER;
        $this->_locOwner = null;
        $this->_locAddr = '';

        $this->_table = "location";
        $this->_tableKey = "locId";
        $this->_history = array();
        $this->_changes = array();
    }

    public function getLocName()
    {
        return $this->_locName;
    }

    public function setLocName($val)
    {
        if (
            strlen($this->_locName) > 0
            && $this->_locName != $val
        ) {
            $this->_history[] = array(
                'col' => 'Location Name',
                'orig' => $this->_locName,
                'new' => $val
            );
        }
        $this->_locName = $val;
    }

    public function getLocDesc()
    {
        return $this->_locDesc;
    }

    public function setLocDesc($val)
    {
        if (
            strlen($this->_locDesc) > 0
            && $this->_locDesc != $val
        ) {
            $this->_history[] = array(
                'col' => 'Location Description',
                'orig' => $this->_locDesc,
                'new' => $val,
            );
        }
        $this->_locDesc = $val;
    }

    public function getLocAddr()
    {
        return $this->_locAddr;
    }

    public function setLocAddr($val)
    {
        if (
            strlen($this->_locAddr) > 0
            && $this->_locAddr != $val
        ) {
            $this->_history[] = array(
                'col' => "Location Address",
                'orig' => $this->_locAddr,
                'new' => $val,
            );
        }
        $this->_locAddr = $val;
    }

    private function _validate()
    {
        $validators = array(
            '_locId' => array(
                Validator::IS_NUM => true,
            ),
            '_locName' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
            ),
            '_locDesc' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 0, 'max' => 2056),
            ),
            '_locOwnerId' => array(
                Validator::IS_NUM => true,
            ),
            '_locAddr' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 2056),
                //Validator::IS_EMAIL => true,
            ),
        );

        foreach ($validators as $key => $val) {
            //echo "Validating: $key [{$this->$key}]\n";
            $v = new Validator($val);
            $v->validate($this->$key);
            if ($v->errNo > 0 ) {
                $errs = $v->getErrors();
                throw new \Exception($errs[0]);
            }
        }
    }

    public function getLocId()
    {
        return $this->_locId;
    }

    public function updateOwner($type, $ownerId)
    {
        if ($type != self::OWNER_GROUP && $type != self::OWNER_USER) {
            throw new \Exception("Owner Type not known");
        }

        switch($type)
        {
            case self::OWNER_GROUP:
                $ug = new UserGroup(UserGroup::RO);
                $valid = $ug->verifyUserGroup($ownerId);
                if (!$valid) {
                    throw new \Exception("Unknown User Group");
                }
                $this->_ownerType = self::OWNER_GROUP;
                $this->_locOwnerId = $ownerId;
                break;
            case self::OWNER_USER:
                $u = new UserModel(UserModel::RO);
                $valid = $u->verifyUser($ownerId);
                if (!$valid) {
                    throw new \Exception("Unknown User");
                }
                    $this->_ownerType = self::OWNER_USER;
                    $this->_locOwnerId = $ownerId;
                break;
        }
        $this->_loadLocOwner();
    }

    private function _loadLocOwner()
    {
        //echo "Owner Type: ".$this->_ownerType."\n";
        if ($this->_ownerType == self::OWNER_USER) {
            $this->locOwner = new UserModel(UserModel::RO);
            $this->locOwner->fetchUserInfo($this->_locOwnerId);
        } else {
            $this->locOwner = new UserGroup(UserGroup::RO);
            $this->locOwner->fetchUserGroup($this->_locOwnerId);
        }
    }

    public function verifyLocation($locId)
    {
        $sql = "select count(*) as cnt from location where locId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($locId));
        $row = $stmt->fetch();
        if ($row['cnt'] > 0 ) {
            return true;
        }
        return false; // implicit else
    }

    public function fetchLocInfo($locId)
    {
        if (($locId <= 0 ) || !is_numeric($locId)) {
            throw new \Exception(
                "Invalid Location Id - ".__FILE__." : ".__LINE__
            );
        }

        $sql = "select * from location where locId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($locId));
        $row = $stmt->fetch();
        //print_r($row);
        $this->_locId = $row['locId'];
        $this->_locName = $row['locName'];
        $this->_locDesc = $row['locDesc'];
        $this->_ownerType = $row['locOwnerType'];
        $this->_locAddr = $row['locAddr'];
        $this->_locOwnerId = $row['locOwner'];
        $this->_loadLocOwner();
        $this->_loadHistory();
    }

    private function _loadHistory()
    {
        $sql = "select * from locationHistory where locId = ? order by mtime";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($this->_locId));
        $rows = $stmt->fetchAll();
        foreach ($rows as $r) {
            $this->_changes[] = array(
                'time' => $r['mtime'],
                'user' => $r['mUser'],
                'deltas' => unserialize($r['changes'])
            );
        }

    }

    public function getChanges()
    {
        return $this->_changes;
    }

    public function save()
    {
        try {
            $this->_validate();
        } catch (\Exception $e) {
            //echo "Validation Exception!\n";
            throw $e;
        }

        if ($this->_locId < 0 ) {
            // new record
            $sql = "insert into location (locName, locDesc, locOwnerType,
                locOwner, locAddr)
                values (?,?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->_locName,
                    $this->_locDesc,
                    $this->_ownerType,
                    $this->_locOwnerId,
                    $this->_locAddr,
                )
            );
        } else {
            // Update to an existing Location
            $sql = "update location set locName = ?, locDesc = ?,
                locOwnerType = ?, locOwner = ?, locAddr = ? where locId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->_locName,
                    $this->_locDesc,
                    $this->_ownerType,
                    $this->_locOwnerId,
                    $this->_locAddr,
                    $this->_locId,
                )
            );
            if (count($this->_history) > 0 ) {
                $sql = "insert into locationHistory set locId = ?,
                mUser = ?, changes = ?";
                $stmt = $this->_db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->_locId,
                        1,
                        serialize($this->_history),
                    )
                );
            }
            //echo "<pre>".print_r($this->_history, true)."</pre>";

        }
    }
}