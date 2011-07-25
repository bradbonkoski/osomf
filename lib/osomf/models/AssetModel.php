<?php

/**
 * Asset Model
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
use osomf\models\LocationModel;
use osomf\models\CiStatus;
use osomf\models\CiType;
use osomf\models\Projects;

/**
 * Asset Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class AssetModel extends DB
{


    private $_ciid;
    public $ciName;
    public $ciDesc;
    private $_ownerType; //User or Group
    private $_ownerId;
    public $owner; //maps into UserModel Class
    private $_projectId;
    public $project; //maps into the ProjectModel Class
    private $_ciStatusId;
    public $ciStatus; //maps into the CiStatusModel Class
    private $_ctime;
    private $_mtime;
    private $_phyParentId;
    public $phyParent; //Maps to Parent asset
    private $_netParentId;
    public $netParent; //Maps to the Network Parent Asset

    private $_ciTypeId;
    public $ciType; //Maps to the CI TypeModel Class
    public $isRetired;
    public $ciSerialNum;
    private $_locId;
    public $loc; //Maps to the LocationModel Class
    public $acquiredDate;
    public $disposalDate;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_ciid = -1;
        $this->ciName = '';
        $this->ciDesc = '';
        $this->_ownerType = self::OWNER_USER;
        $this->_ownerId = -1;
        $this->owner = null;
        $this->_projectId = -1;
        $this->project = null;
        $this->_ciStatusId = -1;
        $this->ciStatus = null;
        $this->_ciTypeId = -1;
        $this->ciType = null;
        $this->_ctime = null;
        $this->_mtime = null;
        $this->_phyParentId = 0;
        $this->phYParent = null;
        $this->_netParentId = 0;
        $this->netParent = null;
        $this->isRetired = 0;
        $this->ciSerialNum = '';
        $this->_locId = -1;
        $this->loc = null;
        $this->acquiredDate = '';
        $this->disposalDate = '';

        $this->_table = "ci";
        $this->_tableKey = "ciid";
        
    }

    public function getAssetName()
    {
        return $this->ciName;
    }


    public function getOwnerId()
    {
        return $this->_ownerId;
    }

    public function getAssetId()
    {
        return $this->_ciid;
    }

    public function getOwnerType()
    {
        return $this->_ownerType;
    }

    public function getAssetTimes()
    {
        return array(
            'created' => $this->_ctime,
            'modified' => $this->_mtime,
        );
    }

    public function verifyCi($ciid)
    {
        $sql = "select count(*) as cnt from ci where ciid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($ciid));
        $row = $stmt->fetch();
        if ($row['cnt'] > 0 ) {
            return true;
        }
        return false;
    }

    public function updateProject($projectId)
    {
        if (!is_numeric($projectId)) {
            throw new \Exception("Invalid Project Id");
        }

        if (!$this->project instanceof ProjectModel) {
            $this->project = new ProjectModel(ProjectModel::RO);
        }
        if ($this->project->verifyProjectId($projectId)) {
            $this->_projectId = $projectId;
            $this->project->fetchProjInfo($this->_projectId);
        } else {
            throw new \Exception("Project Does not Exist");
        }
    }

    public function updateStatus($statusId)
    {
        if (!is_numeric($statusId)) {
            throw new \Exception("Invalid Status Id");
        }

        if (!$this->ciStatus instanceof CiStatus) {
            $this->ciStatus = new CiStatus(CiStatus::RO);
        }
        if ($this->ciStatus->verifyStatusId($statusId)) {
            $this->_ciStatusId = $statusId;
            $this->ciStatus->loadStatus($statusId);
        } else {
            throw new \Exception("Status Not Known");
        }
    }

    public function updateType($typeId)
    {
        if (!is_numeric($typeId)) {
            throw new \Exception("Invalid Type Id");
        }

        if (!$this->ciType instanceof CiType) {
            $this->ciType = new CiType(CiType::RO);
        }
        if ($this->ciType->verifyType($typeId)) {
            $this->_ciTypeId = $typeId;
            $this->ciType->loadType($typeId);
        } else {
            throw new \Exception("Unknown Type");
        }
    }

    public function updatePhyParent($ciid)
    {
        if (!is_numeric($ciid)) {
            throw new \Exception("Invalid CI ID");
        }

        if ($this->_ciid == $ciid) {
            throw new \Exception("Cannot be a Parent of self");
        }

        if (!$this->phyParent instanceof AssetModel) {
            $this->phyParent = new AssetModel(AssetModel::RO);
        }
        if ($this->verifyCi($ciid)) {
            $this->_phyParentId = $ciid;
            $this->phyParent->loadAsset($ciid);
        } else {
            throw new \Exception("Invalid CI");
        }
    }

    public function updateNetParent($ciid)
    {
        if (!is_numeric($ciid)) {
            throw new \Exception("Invalid CI ID");
        }

        if ($this->_ciid == $ciid) {
            throw new \Exception("Cannot be a Parent of self");
        }

        if (!$this->netParent instanceof AssetModel) {
            $this->netParent = new AssetModel(self::RO);
        }
        if ($this->verifyCi($ciid)) {
            $this->_netParentId = $ciid;
            $this->netParent->loadAsset($ciid);
        } else {
            throw new \Exception("Invalid CI");
        }
    }

    public function updateLoc($locId)
    {
        if (!is_numeric($locId)) {
            throw new \Exception("Invalid Location Id");
        }

        if (!$this->loc instanceof LocationModel) {
            $this->loc = new LocationModel(LocationModel::RO);
        }
        if ($this->loc->verifyLocation($locId)) {
            $this->_locId = $locId;
            $this->loc->fetchLocInfo($locId);
        } else {
            throw new \Exception("Unknown Location");
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
                $this->_ownerId = $ownerId;
                break;
            case self::OWNER_USER:
                $u = new UserModel(UserModel::RO);
                $valid = $u->verifyUser($ownerId);
                if (!$valid) {
                    throw new \Exception("Unknown User");
                }
                    $this->_ownerType = self::OWNER_USER;
                    $this->_ownerId = $ownerId;
                break;
        }
        $this->_loadOwner($this->_ownerId, $this->_ownerType, "owner");
    }

    private function _loadOwner($ownerId, $type, $class)
    {
         //echo "Owner Type: ".$this->_ownerType."\n";
        //echo "OwnerId: $ownerId -- Type: $type\n";
        if ($type == self::OWNER_USER) {
            $this->$class = new UserModel(UserModel::RO);
            $this->$class->fetchUserInfo($ownerId);
        } else {
            $this->$class = new UserGroup(UserGroup::RO);
            $this->$class->fetchUserGroup($ownerId);
        }
    }

    private function _validate()
    {
        $validators = array(
            '_ciid' => array(
                Validator::IS_NUM => true,
            ),
            'ciName' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
            ),
            'ciDesc' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 0, 'max' => 2056),
            ),
            '_ownerId' => array(
                Validator::IS_NUM => true,
            ),
            '_projectId' => array(
                Validator::IS_NUM => true,
            ),
            '_ciStatusId' => array(
                Validator::IS_NUM => true,
            ),
            '_ciTypeId' => array(
                Validator::IS_NUM => true,
            ),
            '_locId' => array(
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

    public function loadAsset($assetId)
    {
        if (!is_numeric($assetId) || $assetId <= 0 ) {
            throw new \Exception("Bad Asset Id");
        }

        $sql = "select * from ci where ciid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($assetId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("No CI for id: {$assetId} found");
        }

        $this->_ciid = $assetId;
        $this->ciName = $row['ciName'];
        $this->ciDesc = $row['ciDesc'];
        $this->_ownerType = $row['ownerType'];
        $this->_ownerId = $row['ownerId'];
        $this->_loadOwner($this->_ownerId, $this->_ownerType, 'owner');
        $this->_projectId = $row['projectId'];
        $this->project = new ProjectModel(ProjectModel::RO);
        $this->project->fetchProjInfo($this->_projectId);
        //var_dump($this->project);
        $this->_ciStatusId = $row['statusId'];
        $this->ciStatus = new CiStatus(CiStatus::RO);
        $this->ciStatus->loadStatus($this->_ciStatusId);
        $this->_ciTypeId = $row['ciTypeId'];
        $this->ciType = new CiType(CiType::RO);
        $this->ciType->loadType($this->_ciTypeId);
        $this->_ctime = $row['ctime'];
        $this->_mtime = $row['mtime'];
        $this->_phyParentId = $row['phyParentId'];
        if ($this->_phyParentId > 0 ) {
            $this->phyParent = new AssetModel(AssetModel::RO);
            $this->phyParent->loadAsset($this->_phyParentId);
        }
        $this->_netParentId = $row['netParentId'];
        if ($this->_netParentId > 0 ) {
            $this->netParent = new AssetModel(AssetModel::RO);
            $this->netParent->loadAsset($this->_netParentId);
        }
        $this->isRetired = $row['isRetired'];
        $this->ciSerialNum = $row['ciSerialNum'];
        $this->_locId = $row['locId'];
        $this->loc = new LocationModel(LocationModel::RO);
        $this->loc->fetchLocInfo($this->_locId);
        $this->acquiredDate = $row['acquiredDate'];
        $this->disposalDate = $row['disposalDate'];
    }

    public function setOwner($ownerId)
    {
        
    }

    public function save()
    {
        try {
            $this->_validate();
        } catch (\Exception $e) {
            //echo "Validation Exception!\n";
            throw $e;
        }

        if ($this->_ciid < 0 ) {
            // new record
            $sql = "insert into ci (ciName, ciDesc, ownerType, ownerId,
                projectId, statusId, phyParentId, netParentId, ciTypeId,
                locId, acquiredDate)
                values (?,?,?,?,?,?,?,?,?,?,NOW())";

            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->ciName,
                    $this->ciDesc,
                    $this->_ownerType,
                    $this->_ownerId,
                    $this->_projectId,
                    $this->_ciStatusId,
                    $this->_phyParentId,
                    $this->_netParentId,
                    $this->_ciTypeId,
                    $this->_locId
                )
            );
        } else {
            // Update to an existing Asset
            $sql = "update ci set ciName = :ciName, ciDesc = :ciDesc,
                ciSerialNum = :ciSerial,
                ownerType = :ownType, ownerId = :owner, projectId = :proj,
                statusId = :stat, phyParentId = :phy, netParentId = :net,
                ciTypeId = :type, locId = :loc, acquiredDate = :ad,
                disposalDate = :dd, mtime=NOW() where ciid = :ciid";
            error_log($sql);
            $data = array(
                ':ciName' => $this->ciName,
                ':ciDesc' => $this->ciDesc,
                ':ciSerial' => $this->ciSerialNum,
                ':ownType' => $this->_ownerType,
                ':owner' => $this->_ownerId,
                ':proj' => $this->_projectId,
                ':stat' => $this->_ciStatusId,
                ':phy' => $this->_phyParentId,
                ':net' => $this->_netParentId,
                ':type' => $this->_ciTypeId,
                ':loc' => $this->_locId,
                ':ad' => $this->acquiredDate,
                ':dd' => $this->disposalDate,
                ':ciid' => $this->_ciid,
            );
            error_log(print_r($data, true));
            $stmt = $this->_db->prepare($sql);
            if (!$stmt->execute($data)) {
                $arr = $stmt->errorInfo();
                error_log(print_r($arr, true));
            }

        }
    }

    public function listAssets()
    {
        $sql = "select ciid, ciName, ciSerialNum from ci";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function search($cols, $crit)
    {
//        $cols = array('incidentId','title', 'impact');
//
//        $crit = array('title' => 'incident', 'resolveSteps' => 'some');
        $where = array();
        foreach ($crit as $k=>$v) {
                $where[] = "$k like '%$v%'";
        }

        $sql = "select ".implode(', ', $cols)." from ci";
        if (count($where > 0)) {
                $sql .= " where ".implode(' AND ', $where);
        }

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }
}