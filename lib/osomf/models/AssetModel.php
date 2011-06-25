<?php
namespace osomf\models;

use osomf\DB;
use osomf\Validator;
use osomf\models\LocationModel;
use osomf\models\CiStatus;
use osomf\models\CiType;
use osomf\models\Projects;

/**
* Asset Model Class
*
* @category Model
* @package Asset
* @author Brad Bonkoski <brad.bonkoski@yahoo.com>
* @copyright Copyright (c) 2011
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

    public function __construct($conn)
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
        $this->_phyParentId = -1;
        $this->phYParent = null;
        $this->_netParentId = -1;
        $this->netParent = null;
        $this->isRetired = 0;
        $this->ciSerialNum = '';
        $this->_locId = -1;
        $this->loc = null;
        $this->acquiredDate = '';
        $this->disposalDate = '';
        
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
        $this->disposalDate = $row['displosalDate'];
        //$this->
    }
}