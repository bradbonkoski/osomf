<?php

/**
 * User Controller
 *
 *
 * @category    Controller
 * @package     User
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

use \osomf\models\UserModel;
use \osomf\models\UserGroup;

/**
 * User Controller
 *
 *
 * @category    Controller
 * @package     User
 * @author      Brad Bonkoski <brad.bonkoski@yahoo.com>
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */

class user extends ControllerBase
{

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("user", $action);

    }

    public function home( $params )
    {
        $this->setAction("home");
        $u = new UserModel(UserModel::RO);
        $rows = $u->getAllUsers();
        $this->data['users'] = $rows;
        
        if (array_key_exists("format", $params)) {
            if ($params['format'] == 'xml') {
                $this->setAction("homeXml");
            }
        }
    }

    public function edit ( $params )
    {

    }

    public function load ( $params )
    {
        $this->ac = true;
        $user = new UserModel(UserModel::RW);
        $data = $_POST['data'];
        echo "Data is:\n";
        print_r($data);
        $xml = simplexml_load_string($data);
        foreach ($xml->user as $u) {
            print_r($u);
            try {
                $user->fetchUserByUserName((string)$u->uname);
                $user->fname = (string) $u->fname;
                $user->lname = (string) $u->lname;
                $user->phone = (string) $u->phone;
                $user->email = (string) $u->email;
                $user->pager = (string) $u->pager;
                $user->save();
            } catch (\Exception $e) {
                //must be a new user...
                $newUser = new UserModel(UserModel::RW);
                $newUser->uname = (string)$u->uname;
                $newUser->fname = (string) $u->fname;
                $newUser->lname = (string) $u->lname;
                $newUser->phone = (string) $u->phone;
                $newUser->email = (string) $u->email;
                $newUser->pager = (string) $u->pager;
                $newUser->save();
            }
        }
    }

    public function view( $params )
    {
        //echo "<pre>".print_r($_COOKIE, true)."</pre>";
        $this->setAction("view");
        //echo "Params are: $params\n";
        $params = $this->parseParams($params);
        //echo "<pre>".print_r($this->parseParams($params), true)."</pre>";
        if (array_key_exists("format", $params)) {
            if ($params['format'] == 'xml') {
                $this->setAction("viewXml");
            }
        }
        $userId = $params[0];
        if (!is_numeric($userId)) {
            //echo "ERROR";
            return $this->home($params);
        }

        $u = new UserModel(UserModel::RO);
        try {
            $u->fetchUserInfo($userId);

            //echo "User: $u\n";
            $this->data['title'] =
                "User Data for: {$u->fname} {$u->lname} ({$u->uname})";
            $this->data['username'] = $u->uname;
            $this->data['fullname'] = $u->fname ." ". $u->lname;
            $this->data['email'] = $u->email;
            $this->data['phone'] = $u->phone;
            $this->data['pager'] = $u->pager;
            $this->data['userid'] = $u->getUserId();

            //Get the User's User Group
            $ug = new UserGroup(UserGroup::RO);
            $ret = $ug->getGroupsForUser($userId);
            //print_r($ret);
            foreach ($ret as $gid => $group) {
                $this->data['groups'][$gid] = $group;

            }
        } catch (Exception $e) {
            $this->data['title'] = $e->getMessage();
        }

    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        $u = new UserModel(UserModel::RO);

        if ($this->_test) {
            return json_encode($u->autocomplete("uname", $str[1]));
        }

        // @codeCoverageIgnoreStart
        echo json_encode($u->autocomplete("uname", $str[1]));
        // @codeCoverageIgnoreEnd
    }
}
