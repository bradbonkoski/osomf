<?php
namespace osomf\models;

use osomf\DB;
 
class CiStatus extends DB
{

    private $_ciStatusId;
    public $statusName;
    public $statusDesc;

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }
        parent::__construct("omf_assets", $conn);

        $this->_ciStatus = -1;
        $this->statusName = '';
        $this->statusDesc = '';
    }

    public function loadStatus($statusId)
    {
        if (!is_numeric($statusId) || $statusId <= 0 ) {
            throw new \Exception("Bad Status Id");
        }

        $sql = "select * from ciStatus where ciStatusId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($statusId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Status id {$statusId} does not exist");
        }

        $this->_ciStatusId = $statusId;
        $this->statusName = $row['statusName'];
        $this->statusDesc = $row['statusDesc'];
    }

}
