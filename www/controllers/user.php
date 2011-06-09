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
        $this->setAction("view");
        //echo "Params are: $params\n";
        $parms = $this->parseParams($params);
        //echo "<pre>".print_r($this->parseParams($params), true)."</pre>";
        if (array_key_exists("format", $parms)) {
            if ($parms['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }
        $userId = $params[0];
        if (!is_numeric($userId)) {
            echo "ERROR";
        }

        $u = new UserModel(UserModel::RO);
        //echo "Fetching user id: {$params[0]}\n";
        $u->fetchUserInfo($userId);
        //echo "User: $u\n";
        $this->data['title'] = "User Data for: {$u->fname} {$u->lname} ({$u->uname})";
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
//        foreach ($ug as $group) {
//            $g = new UserGroup(UserGroup::REUSE);
//            $g->fetchUserGroup($group);
//            $this->data['groups'][] = array(
//                'GroupName' => $g->groupName,
//                'GroupDesc' => $g->groupDesc,
//                'GroupPager' => $g->pager,
//                'GroupPhone' => $g->phone,
//            );
//        }
//
//        print_r($this->data);

    }
}
