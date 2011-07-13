<?php
namespace osomf\models;

use osomf\DB;
use osomf\Validator;
use osomf\models\User;
use osomf\models\UserGroup;

/**
* Project Model Class
*
* @category Model
* @package Project
* @author Brad Bonkoski <brad.bonkoski@yahoo.com>
* @copyright Copyright (c) 2011
*/

class ProjectModel extends DB
{
    private $_projId;
    public $projName;
    public $projDesc;
    private $_ownerType;
    public $projOwner; //maps to the User{Group}Model Class
    private $_projOwnerId;


    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_projId = -1;
        $this->projName = '';
        $this->projDesc = '';
        $this->_ownerType = self::OWNER_USER;
        $this->projOwner = null;
        $this->_table = "projects";
        $this->_tableKey = "projId";
    }

    public function getProjId()
    {
        return $this->_projId;
    }

    public function getProjName()
    {
        return $this->projName;
    }


    private function _validate()
    {
        $validators = array(
            '_projId' => array(
                Validator::IS_NUM => true,
            ),
            'projName' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
            ),
            'projDesc' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 0, 'max' => 2056),
            ),
            '_projOwnerId' => array(
                Validator::IS_NUM => true,
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
                $this->_projOwnerId = $ownerId;
                break;
            case self::OWNER_USER:
                $u = new UserModel(UserModel::RO);
                $valid = $u->verifyUser($ownerId);
                if (!$valid) {
                    throw new \Exception("Unknown User");
                }
                    $this->_ownerType = self::OWNER_USER;
                    $this->_projOwnerId = $ownerId;
                break;
        }
        $this->_loadProjOwner();
    }

    private function _loadProjOwner()
    {
        //echo "Owner Type: ".$this->_ownerType."\n";
        if ($this->_ownerType == self::OWNER_USER) {
            $this->projOwner = new UserModel(UserModel::RO);
            $this->projOwner->fetchUserInfo($this->_projOwnerId);
        } else {
            $this->projOwner = new UserGroup(UserGroup::RO);
            $this->projOwner->fetchUserGroup($this->_projOwnerId);
        }
    }

    public function verifyProjectId($projId)
    {
        $sql = "select count(*) as cnt from projects where projId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($projId));
        $row = $stmt->fetch();
        if ($row['cnt'] > 0 ) {
            return true;
        }
        return false; //implicit else
    }

    public function fetchProjInfo($projId)
    {
        if (($projId <= 0 ) || !is_numeric($projId)) {
            throw new \Exception(
                "Invalid Project Id - ".__FILE__." : ".__LINE__
            );
        }

        $sql = "select * from projects where projId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($projId));
        $row = $stmt->fetch();
        //print_r($row);
        $this->_projId = $row['projId'];
        $this->projName = $row['projName'];
        $this->projDesc = $row['projDesc'];
        $this->_ownerType = $row['projOwnerType'];
        $this->_projOwnerId = $row['projOwner'];
        $this->_loadProjOwner();
    }

    public function save()
    {
        try {
            $this->_validate();
        } catch (\Exception $e) {
            throw $e;
        }

        if ($this->_projId < 0 ) {
            // new record
            $sql = "insert into projects (projName, projDesc,
                projOwnerType, projOwner)
                values (?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->projName,
                    $this->projDesc,
                    $this->_ownerType,
                    $this->_projOwnerId,
                )
            );
        } else {
            // Update to an existing User
            $sql = "update projects set projName = ?, projDesc = ?,
                projOwnerType = ?, projOwner = ? where projId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->projName,
                    $this->projDesc,
                    $this->_ownerType,
                    $this->_projOwnerId,
                    $this->_projId,
                )
            );

        }
    }


}