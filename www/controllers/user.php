<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 9:00 AM
 * To change this template use File | Settings | File Templates.
 */

use \osomf\models\UserModel;

class user extends ControllerBase
{

    public function __construct( $controller = "", $action = "")
    {
        parent::__construct("user", $action);
    }

    public function view( $params )
    {
        $this->setAction("view");
        //echo "I'm in the User View!<br/>";
        $parms = $this->parseParams($params);
        //echo "<pre>".print_r($this->parseParams($params), true)."</pre>";
        if (array_key_exists("format", $parms)) {
            if ($parms['format'] == 'xml') {
                //swap out the view for an XML one...
            }
        }

        $u = new UserModel(UserModel::RO);
        //echo "Fetching user id: {$params[0]}\n";
        $u->fetchUserInfo($params[0]);
        echo "User: $u\n";
        $this->data['title'] = "User Data for: {$u->fname} {$u->lname} ({$u->uname})";


        $this->data['testArray'] = array('some', 'one', 'is','here');

    }

    public function add( $params )
    {
        echo "I'm in the User add action<br/>";
        echo "<pre>".print_r($params, true)."</pre>";
    }
}
