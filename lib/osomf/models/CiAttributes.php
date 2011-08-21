<?php

/**
 * Configuration Item Attributes Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

namespace osomf\models;

use \osomf\DB;

/**
 * Configuration Item Attributes Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

class CiAttributes extends DB
{

    private $_attrId;
    private $_attrname;

    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_attrId = -1;
        $this->_attrname = '';

        $this->_table = "Attributes";
        $this->_tableKey = "attrId";
    }

    public function getAttrId()
    {
        return $this->_attrId;
    }

    public function verifyAttributes($attrId)
    {
        $sql = "select count(*) as cnt from Attributes where attrId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($attrId));
        $row = $stmt->fetch();
        if ($row['cnt'] > 0 ) {
            return true;
        }
        return false; //implicit else
    }

    public function loadType($attrId)
    {
        if (!is_numeric($attrId) || $attrId <= 0 ) {
            throw new \Exception("Bad Type Id");
        }

        $sql = "select * from Attributes where attrId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($attrId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Attribute id {$attrId} does not exist");
        }

        $this->_attrId = $attrId;
        $this->_attrname = $row['attrName'];
    }


}
