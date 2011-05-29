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
     * FetchUserInfo Test
     *
     * @test
     */
    public function fetchUserInfoTestOne()
    {
        $u = new User(USER::RO);
        $u->fetchUserInfo(3);
        $this->assertEquals('brad2', $u->uname);
        $this->assertEquals('800-332-5555', $u->phone);
    }

    /**
     * FetchUserInfo Test - Base User Id
     * @test
     */
    public function fetchUserInfoBadeUserId()
    {
        $u = new User(USER::RO);
        try {
            $u->fetchUserInfo("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }


    /**
     * @test
     */
    public function getUserInfoFromUserName()
    {
        $u = new User(USER::RO);
        $u->fetchUserByUserName('bradb');
        $this->assertEquals('bradb', $u->uname);
        $this->assertEquals('Bonkoski', $u->lname);
        echo $u;
    }

    /**
     * Invalid UserName for getUserInfoFromuserName
     * @test
     */
    public function getuserInfoFromUserNameBadUserName()
    {
        $u = new User(User::RO);
        try {
            $u->fetchUserByUserName('');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * GetUserByUserName Test with a valid, but not found username
     * @test
     * @group UserNotFound
     */
    public function getUserByUserNameNoSuchUser()
    {
        $u = new User(USER::RO);
        try {
            $u->fetchUserByUserName("samIamHere");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
