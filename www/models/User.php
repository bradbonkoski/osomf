<?php

require_once('lib/DB.php');

/**
* User/UserGroup Model Class
*
* @category Model
* @package User
* @author Brad Bonkoski <brad.bonkoski@yahoo.com>
* @copyright Copyright (c) 2011
*/

class User extends DB
{
    const RO = "ro";
    const RW = "rw";

    /**
     * @var array
     */
    private $_validConn = array(self::RO, self::RW);

    /**
     * @throws Exception
     * @param  $conn
     */
    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new Exception("Invalid Connection");
        }

        parent::__construct("omf_users", $conn);
    }

    /**
     * @throws Exception
     * @param int $groupId
     * @return array
     */
    public function getGroupMembers($groupId = 0) 
    {
        //echo "Group Id is: $groupId\n";
        if( $groupId <= 0 || !is_numeric($groupId) ) {
            throw new Exception("Invalid Group Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from users_groups where ugid = ? and status = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId, 'member'));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * @throws Exception
     * @param int $groupId
     * @return array
     */
    public function getGroupAdmins($groupId = 0)
    {
        if( $groupId <= 0 || !is_numeric($groupId) ) {
            throw new Exception("Invalid Group Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from users_groups where ugid = ? and status = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId, 'admin'));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * @throws Exception
     * @param int $groupId
     * @return array
     */
    public function getGroupAll($groupId = 0)
    {
        if( $groupId <= 0 || !is_numeric($groupId) ) {
            throw new Exception("Invalid Group Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from users_groups where ugid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * @throws Exception
     * @param  $groupId
     * @return array
     */
    public function getUserGroupDetails($groupId)
    {
        if( $groupId <= 0 || !is_numeric($groupId) ) {
            throw new Exception("Invalid Group Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from userGroup where ugid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
