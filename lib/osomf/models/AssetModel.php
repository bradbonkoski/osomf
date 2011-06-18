<?php
namespace osomf\models;

use osomf\DB;
use osomf\Validator;

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
    private $_owner; //maps into UserModel Class
    private $_project; //maps into the ProjectModel Class
    private $_ciStatus; //maps into the CiStatusModel Class
    private $_ctime;
    private $_mtime;
    private $_phyParent; //Maps to Parent asset
    private $_netParent; //Maps to the Network Parent Asset
    private $_ciType; //Maps to the CI TypeModel Class
    public $isRetired;
    public $ciSerialNum;
    private $_loc; //Maps to the LocationModel Class
    public $acquiredDate;
    public $disposalDate;

    public function _construct($assetId = -1)
    {
        if ($assetId <= 0 ) {
            
        }
    }
}