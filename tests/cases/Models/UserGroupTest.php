<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 5/28/11
 * Time: 5:33 PM
 * To change this template use File | Settings | File Templates.
 */
 

require_once 'PHPUnit/Framework/TestCase.php';

//require_once 'www/models/UserGroup.php';
use osomf\models\UserGroup;

/**
 * @group UserGroup
 */

class UserGroupTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function UserGroupOne()
    {
        $u = new UserGroup(UserGroup::RO);
        $u->fetchUserGroup(2);
        //var_dump($u);
        $this->assertEquals('oncall1', $u->groupName);
        $this->assertEquals('Top Level On Call', $u->groupDesc);
        $this->assertEquals('bradb', $u->users[0]->uname);
        $this->assertEquals('admin', $u->users[0]->status);
        $this->assertEquals('member', $u->users[1]->status);
    }


    /**
     * Test for Pulling All Groups a user is a member of
     * @test
     * @group UsersGroups
     */
    public function getUsersUsersGroups()
    {
        $u = new UserGroup(UserGroup::RO);
        $ret = $u->getGroupsForUser(1);
        //print_r($ret);
        $this->assertEquals(2, count($ret));
        $this->assertEquals(4, count($ret[2]));
        $this->assertEquals(4, count($ret[1]));

    }
}
