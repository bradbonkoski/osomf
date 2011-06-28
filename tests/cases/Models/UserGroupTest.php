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

    /**
     * @test
     * Try to fetch a non existant user group
     */
    public function getNonExistantUserGroup()
    {
        $u = new UserGroup(UserGroup::RO);
        try {
            $u->fetchUserGroup(1000000);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Test the VerifyUserGroup Method
     */
    public function verifyUserGroup()
    {
        $u = new UserGroup(UserGroup::RO);
        $ret = $u->verifyUserGroup(1000000);
        $this->assertTrue(!$ret);
    }

    /**
     * @test
     * Bad Connector
     */
    public function badConn()
    {
        try {
            $u = new UserGroup("something");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Bad user Group Fetch Test
     */
    public function badUserGroupFetch()
    {
        $ug = new UserGroup(UserGroup::RO);
        try {
            $ug->fetchUserGroup(1000029394030);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Bad ID for Groups for users
     */
    public function badIdForGroupsForUsers()
    {
        $ug = new UserGroup(UserGroup::RO);
        try {
            $ug->getGroupsForUser("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * get all groups test
     */
    public function getAllGroups()
    {
        $ug = new UserGroup(UserGroup::RO);
        $ret = $ug->getAllGroups();
        $this->assertTrue(count($ret)> 0 );
    }

    /**
     * @test
     * Bad Group Id for Fetch User Group
     */
    public function badIdForFetchUserGroup()
    {
        $ug = new UserGroup(UserGroup::RO);
        try {
            $ug->fetchUserGroup("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Bad Group Id for User Group Verification
     */
    public function badIdForUserGroupVerify()
    {
        $ug = new UserGroup(UserGroup::RO);
        try {
            $ug->verifyUserGroup("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
