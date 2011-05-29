<?php


//require_once('lib/DB.php');
use osomf\DB;

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

    private $_userId;
    public $uname;
    public $fname;
    public $lname;
    public $email;
    public $phone;
    public $pager;
    public $status;

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
        $this->_userId = -1;
        $this->uname = "";
        $this->fname = "";
        $this->lname = "";
        $this->email = "";
        $this->phone = "";
        $this->pager = "";
        $this->status = '';
    }

    public function fetchUserInfo($userId)
    {
        if( ($userId <= 0 ) || !is_numeric($userId)) {
            throw new Exception("Invalid User Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from users where userId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($userId));
        $row = $stmt->fetch();
        //print_r($row);
        $this->_userId = $row['userId'];
        $this->uname = $row['uname'];
        $this->fname = $row['fname'];
        $this->lname = $row['lname'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->pager = $row['pager'];
    }

    public function __toString()
    {
        $ret = "
            \tUserId: {$this->_userId}\n
            \tUser Name: {$this->uname}\n
            \tFirst name: {$this->fname}\n
            \tLast Name: {$this->lname}\n
            \tEmail: {$this->email}\n
            \tPhone: {$this->phone}\n
            \tPager: {$this->pager}\n";
        return $ret;

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
        $rows = $stmt->fetchAll();
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
        $rows = $stmt->fetchAll();
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
        $rows = $stmt->fetchAll();
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
        return $stmt->fetchAll();
    }


    /**
     * @throws Exception
     * @param array $users
     * @return array
     */
    public function getUserInfo($users = array())
    {
        if(count($users) <= 0 || !is_array($users)) {
            throw new Exception("Invalid User Id - ".__FILE__." : ".__LINE__);
        }
        foreach($users as $u) {
            if(!is_numeric($u)) {
                throw new Exception("User: $u is non numeric - ".__FILE.__." : ".__LINE__);
            }
        }

        $sql = "select * from users where userId in (?)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(implode(',', $users)));
        return $stmt->fetchAll();
    }

    public function addUser($uname, $fname, $lname, $email, $phone = null, $pager = null)
    {

    }
}
