<?php

require_once('lib/DB.php');

class ChangeModel extends DB
{
    const RO = "ro";
    const RW = "rw";
        
    private $_validConn = array(self::RO, self::RW);

    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new Exception("Invalid Connection");
        }

        parent::__construct("change", $conn);
    }
}
