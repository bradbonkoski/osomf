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
                Validator::IS_NUMERIC => true,
            ),
            'locName' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
            ),
            'locDesc' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 2056),
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
        //echo "Owner Type: ".$this->_ownerType."\n";
        if ($this->_ownerType == self::OWNER_USER) {
            $this->locOwner = new UserModel(UserModel::RO);
            $this->locOwner->fetchUserInfo($row['locOwner']);
        } else {
            $this->locOwner = new UserGroup(UserGroup::RO);
            $this->locOwner->fetchUserGroup($row['locOwner']);
        }
    }


}