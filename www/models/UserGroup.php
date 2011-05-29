<?php


/**
 * User: bradb
 * Date: 5/28/11
 * Time: 3:53 PM
 * Copyright: (c) 2011 FHC
 */

use osomf\DB;
require_once('www/models/User.php');

class UserGroup extends DB {

    private $_ugid;
    public $groupName;
    public $groupDesc;
    public $phone;
    public $pager;
    public $users = array();


    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new Exception("Invalid Connection");
        }

        parent::__construct("omf_users", $conn);

        $this->_ugid = -1;
        $this->groupName = "";
        $this->groupDesc = "";
        $this->phone = "";
        $this->pager = "";
        $this->users = array();
    }

    private function _fetchGroupInfo($groupId)
    {
        $sql = "select * from userGroup where ugid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId));
        $row = $stmt->fetch();
        $this->_ugid = $row['ugid'];
        $this->groupName = $row['groupName'];
        $this->groupDesc = $row['groupDesc'];
        $this->phone = $row['phone'];
        $this->pager = $row['pager'];

    }

    private function _loadUsers($groupId)
    {
        $sql = "select * from users_groups where ugid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId));
        $rows = $stmt->fetchAll();
        //print_r($rows);
        foreach($rows as $row) {
            //echo "User Info for: {$row['userid']}\n";
            $u = new User(User::RO);
            $u->fetchUserInfo($row['userid']);
            $u->status = $row['status'];
            //echo $u;
            $this->users[] = $u;
        }
    }

    public function fetchUserGroup($groupId)
    {
        if( $groupId <= 0 || !is_numeric($groupId) ) {
            throw new Exception("Invalid Group Id - ".__FILE__." : ".__LINE__);
        }

        $this->_fetchGroupInfo($groupId);

        $this->_loadUsers($groupId);


    }
}
