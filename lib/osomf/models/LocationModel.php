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
    public $locName;
    public $locDesc;
    private $_ownerType;
    public $locOwner; //maps to the UserModel Class
    private $_locOwnerId;
    public $locAddr;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_locId = -1;
        $this->locname = '';
        $this->locDesc = '';
        $this->_ownerType = self::OWNER_USER;
        $this->locOwner = null;
        $this->_locAddr = '';
    }

    private function _validate()
    {
        $validators = array(
            '_locId' => array(
                Validator::IS_NUM => true,
            ),
            'locName' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
            ),
            'locDesc' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 0, 'max' => 2056),
            ),
            '_locOwnerId' => array(
                Validator::IS_NUM => true,
            ),
            'locAddr' => array(
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

    public function fetchLocInfo($locId)
    {
        if (($locId <= 0 ) || !is_numeric($locId)) {
            throw new \Exception("Invalid User Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from location where locId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($locId));
        $row = $stmt->fetch();
        //print_r($row);
        $this->_locId = $row['locId'];
        $this->locName = $row['locName'];
        $this->locDesc = $row['locDesc'];
        $this->_ownerType = $row['locOwnerType'];
        $this->locAddr = $row['locAddr'];
        $this->_locOwnerId = $row['locOwner'];
        $this->_loadLocOwner();
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
            $sql = "insert into location (locName, locDesc, locOwnerType, locOwner, locAddr)
                values (?,?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->locName,
                    $this->locDesc,
                    $this->_ownerType,
                    $this->_locOwnerId,
                    $this->locAddr,
                )
            );
        } else {
            // Update to an existing User
            $sql = "update location set locName = ?, locDesc = ?,
                locOwnerType = ?, locOwner = ?, locAddr = ? where locId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->locName,
                    $this->locDesc,
                    $this->_ownerType,
                    $this->_locOwnerId,
                    $this->locAddr,
                    $this->_locId,
                )
            );

        }
    }


}