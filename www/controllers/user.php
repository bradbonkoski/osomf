<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 9:00 AM
 * To change this template use File | Settings | File Templates.
 */

use \osomf\models\UserModel;
use \osomf\models\UserGroup;

class user extends ControllerBase
{

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("user", $action);

    }

    public function view( $params )
    {
        //echo "<pre>".print_r($_COOKIE, true)."</pre>";
        $this->setAction("view");
        //echo "Params are: $params\n";
        $parms = $this->parseParams($params);
        //echo "<pre>".print_r($this->parseParams($params), true)."</pre>";
        if (array_key_exists("format", $parms)) {
            if ($parms['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }
        $userId = $parms[0];
        if (!is_numeric($userId)) {
            echo "ERROR";
            //redirect to the home here!
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
}
