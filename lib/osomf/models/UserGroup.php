<?php
namespace osomf\models;

/**
 * User: bradb
 * Date: 5/28/11
 * Time: 3:53 PM
 * Copyright: (c) 2011 FHC
 */

use osomf\DB;
use osomf\models\UserModel;


class UserGroup extends DB
{
    private $_ugid;
    public $groupName;
    public $groupDesc;
    public $phone;
    public $pager;
    public $users = array();


    public function __construct($conn)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
        }

        parent::__construct("omf_users", $conn);

        $this->_ugid = -1;
        $this->groupName = "";
        $this->groupDesc = "";
        $this->phone = "";
        $this->pager = "";
        $this->users = array();

        $this->_table = "userGroup";
        $this->_tableKey = "ugid";
    }

    private function _fetchGroupInfo($groupId)
    {
        $sql = "select * from userGroup where ugid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId));
        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("UserGroup {$groupId} does not exist");
        }
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
        foreach ($rows as $row) {
            $u = new UserModel(UserModel::RO);
            $u->fetchUserInfo($row['userid']);
            $u->status = $row['status'];
            //echo $u;
            $this->users[] = $u;
        }
    }

    public function fetchUserGroup($groupId)
    {
        if ($groupId <= 0 || !is_numeric($groupId)) {
            throw new \Exception(
                "Invalid Group Id - ".__FILE__." : ".__LINE__
            );
        }

        try {
            $this->_fetchGroupInfo($groupId);

            $this->_loadUsers($groupId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function verifyUserGroup($groupId)
    {
        if ($groupId <= 0 || !is_numeric($groupId)) {
            throw new \Exception(
                "Invalid Group Id - ".__FILE__." : ".__LINE__
            );
        }

        $sql = "select count(*) as cnt from userGroup where ugid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($groupId));
        $row = $stmt->fetch();
        if ($row['cnt'] == 0 ) {
            return false;
        }
        return true;
    }

    public function getGroupsForUser($userId)
    {
        if ($userId <= 0 || !is_numeric($userId)) {
            throw new \Exception(
                "Invalid User Id - ".__FILE__." : ".__LINE__
            );
        }
        $sql = "select ugid, status from users_groups where userid = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($userId));
        $rows = $stmt->fetchAll();
        $ret = array();
        foreach ($rows as $r) {
            $gid = $r['ugid'];
            $this-> _fetchGroupInfo($gid);
            $ret[$gid] = array(
                'GroupName' => $this->groupName,
                'GroupDesc' => $this->groupDesc,
                'GroupPhone' => $this->phone,
                'GroupPager' => $this->pager,
                'status' => $r['status'],
            );
        }
        return $ret;
    }

    public function getAllGroups()
    {
        $sql = "select * from userGroup";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }
}
