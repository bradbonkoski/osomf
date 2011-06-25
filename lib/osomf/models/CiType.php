<?php
namespace osomf\models;

use \osomf\DB;
 
class CiType extends DB {

    private $_ciTypeId;
    public $typeName;
    public $typeDesc;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_ciType = -1;
        $this->typeName = '';
        $this->typeDesc = '';
    }

    public function loadType($typeId)
    {
        if (!is_numeric($typeId) || $typeId <= 0 ) {
            throw new \Exception("Bad Type Id");
        }

        $sql = "select * from ciType where ciTypeId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($typeId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("CIType id {$typeId} does not exist");
        }

        $this->_ciTypeId = $typeId;
        $this->typeName = $row['typeName'];
        $this->typeDesc = $row['typeDesc'];
    }

}
