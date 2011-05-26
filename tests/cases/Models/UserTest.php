<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'www/models/User.php';

/**
 * @group User
 */

class UserTest extends PHPUnit_Framework_TestCase
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
    public function initial()
    {
        $c = new User(User::RO);
    }

    /**
     * @test
    */
    public function usersGroupsOne()
    {
        $c = new User(User::RO);
        $ret = $c->getGroupMembers(2);
        $this->assertEquals(1, count($ret));
    }

    /**
     * @return void
     * @test
     */
    public function usersGroupAdminsOne()
    {
        $c = new User(User::RO);
        $ret = $c->getGroupAdmins(2);
        $this->assertEquals(1, count($ret));
    }

      /**
     * @return void
     * @test
     */
    public function usersGroupAllOne()
    {
        $c = new User(User::RO);
        $ret = $c->getGroupAll(2);
        $this->assertEquals(2, count($ret));
    }

    /**
     * @test
     */
    public function userGroupDetailsOne()
    {
        $c = new User(User::RO);
        $ret = $c->getUserGroupDetails(1);
        //print_r($ret);
        $this->assertEquals(1, count($ret));
        $this->assertEquals('group1', $ret[0]['groupName']);
    }
}
