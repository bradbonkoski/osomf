<?php

/**
 * User Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

namespace osomf\models;

use osomf\DB;
use osomf\Validator;

/**
 * User Model
 *
 *
 * @category    Library
 * @package     Model
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 *
 */

class UserModel extends DB
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
    public function __construct($conn = self::RO)
    {
        if (!in_array($conn, $this->_validConn)) {
            throw new \Exception("Invalid Connection");
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

        $this->_table = "users";
        $this->_tableKey = "userId";
    }

    private function _validate()
    {
        $validators = array(
            'uname' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 32),
            ),
            'fname' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 32),
            ),
            'lname' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 64),
            ),
            'email' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 128),
                //Validator::IS_EMAIL => true,
            ),
            'phone' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 1, 'max' => 32),
                Validator::IS_PHONE => true,
            ),
            'pager' => array(
                Validator::IS_STRING => true,
                Validator::STRLEN => array('min' => 0, 'max' => 32),
                Validator::IS_PHONE => true,
            )
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

    public function getUserId()
    {
        return $this->_userId;
    }

    public function checkPassword($userId)
    {
        if (($userId <= 0 ) || !is_numeric($userId)) {
            throw new \Exception("Invalid User Id - ".__FILE__." : ".__LINE__);
        }
        $sql = "select pass from user_pass where userId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($userId));
        $row = $stmt->fetchAll();
        return $row[0]['pass'];
    }

    public function setPassword($pass)
    {
        $sql = "insert into user_pass set userId = ?, pass=?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($this->_userId, $pass));
    }

    /**
     * @throws Exception
     * @param  $userId
     * @return void
     */
    public function fetchUserInfo($userId)
    {
        if (($userId <= 0 ) || !is_numeric($userId)) {
            throw new \Exception("Invalid User Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select * from users where userId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($userId));
        $row = $stmt->fetch();
        //print_r($row);
        if ($row === false) {
            throw new \Exception("User {$userId} does not exist");
        }
        $this->_userId = $row['userId'];
        $this->uname = $row['uname'];
        $this->fname = $row['fname'];
        $this->lname = $row['lname'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->pager = $row['pager'];
    }

    public function verifyUser($userId)
    {
        if (($userId <= 0 ) || !is_numeric($userId)) {
            throw new \Exception("Invalid User Id - ".__FILE__." : ".__LINE__);
        }

        $sql = "select count(*) as cnt from users where userId = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($userId));
        $row = $stmt->fetch();
        if ($row['cnt'] <= 0 ) {
            return false;
        }
        return true;
    }

    /**
     * @throws Exception
     * @param  $username
     * @return void
     */
    public function fetchUserByUserName($username)
    {
        if (strlen($username) <= 0) {
            throw new \Exception("Invalid UserName - ".__FILE__." : ".__LINE__);
        }
        $sql = "select userId from users where uname = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array($username));
        $row = $stmt->fetch();
        if (count($row) <= 0 || $row === false) {
            throw new \Exception("No Such User");
        }
        return $this->fetchUserInfo($row['userId']);
    }

    /**
     * @return string
     */
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
    //TODO Move this out of the User Model Class!

//    public function getGroupAdmins($groupId = 0)
//    {
//        if ($groupId <= 0 || !is_numeric($groupId)) {
//            throw new Exception(
//              "Invalid Group Id - ".__FILE__." : ".__LINE__
//              );
//        }
//
//        $sql = "select * from users_groups where ugid = ? and status = ?";
//        $stmt = $this->_db->prepare($sql);
//        $stmt->execute(array($groupId, 'admin'));
//        $rows = $stmt->fetchAll();
//        return $rows;
//    }

    public function getAllUsers()
    {
        $sql = "select * from users";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function save()
    {

        try {
            $this->_validate();
        } catch (\Exception $e) {
            //echo "Validation Exception!\n";
            throw $e;
        }
            
        if ($this->_userId < 0 ) {
            // new record
            $sql = "insert into users (uname, fname, lname, email, phone, pager)
                values (?,?,?,?,?,?)";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->uname,
                    $this->fname,
                    $this->lname,
                    $this->email,
                    $this->phone,
                    $this->pager,
                )
            );
        } else {
            // Update to an existing User
            $sql = "update users set uname = ?, fname = ?,
                email = ?, phone = ?, pager = ? where userId = ?";
            $stmt = $this->_db->prepare($sql);
            $stmt->execute(
                array(
                    $this->uname,
                    $this->fname,
                    $this->email,
                    $this->phone,
                    $this->pager,
                    $this->_userId,
                )
            );

        }
    }
}
