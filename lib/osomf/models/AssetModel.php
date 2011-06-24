<?php
namespace osomf\models;

use osomf\DB;
use osomf\Validator;
use osomf\models\LocationModel;

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
    private $_project; //maps into the ProjectModel Class
    private $_ciStatus; //maps into the CiStatusModel Class
    private $_ctime;
    private $_mtime;
    private $_phyParentId;
    public $phyParent; //Maps to Parent asset
    private $_netParentId;
    public $netParent; //Maps to the Network Parent Asset

    private $_ciType; //Maps to the CI TypeModel Class
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
        $this->_project = -1;
        
    }
}