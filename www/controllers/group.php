<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 9:00 AM
 * To change this template use File | Settings | File Templates.
 */

use \osomf\models\UserGroup;

class group extends ControllerBase
{

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("group", $action);

    }

    public function home()
    {
        $this->setAction("home");
        $ug = new UserGroup(UserGroup::RO);
        $data = $ug->getAllGroups();
        $this->data['title'] = "All User Groups";
        $this->data['groups'] = $data;
    }

    public function view( $params )
    {
        $this->setAction("view");
        $parms = $this->parseParams($params);
        if (array_key_exists("format", $parms)) {
            if ($parms['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }
        $groupId = $params[0];
        if (!is_numeric($groupId)) {
            return $this->home();
        }

        $ug = new UserGroup(UserGroup::RO);
        $ug->fetchUserGroup($groupId);
        //echo "User: $u\n";
        $this->data['title'] = "User Group Info for: {$ug->groupName}";
        $this->data['groupName'] = $ug->groupName;
        $this->data['groupDesc'] = $ug->groupDesc;
        $this->data['phone'] = $ug->phone;
        $this->data['pager'] = $ug->pager;

        foreach($ug->users as $u) {
            $arr = array(
                'uid' => $u->getUserId(),
                'uname' => $u->uname,
                'name' => $u->fname." ".$u->lname,
                'email' => $u->email,
                'phone' => $u->phone,
                'pager' => $u->pager,
                'status' => $u->status,
            );
            $this->data['users'][] = $arr;
        }


    }

    public function autocomplete( $params )
    {
        $this->ac = true;
        $str = explode('=', $params);
        $ug = new UserGroup(UserGroup::RO);
        echo json_encode($ug->autocomplete("groupName", $str[1]));
    }
}
