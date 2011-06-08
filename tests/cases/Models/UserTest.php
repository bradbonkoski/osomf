<?php

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\User;
//require_once 'www/models/User.php';

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

    /**
     * @test
     */
    public function newUser1()
    {
        $u = new User(USER::RW);
        $u->uname = "fitzer1";
        $u->fname = "Fitzgerald";
        $u->lname = "Beth";
        $u->email = "fitzer@yahoo.com";
        $u->phone = "800-323-4432";
        $u->save();
    }

    /**
     * @test
     */
    public function newUserBadPhone()
    {
        $u = new User(USER::RW);
        $u->uname = "fitzer1";
        $u->fname = "Fitzgerald";
        $u->lname = "Beth";
        $u->email = "fitzer@yahoo.com";
        $u->phone = "800-fd323-4432";
        try {
            $u->save();
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }


    /**
     * Test for Updating a user (id: 10 in seeded set)
     * @test
     */
    public function UpdateTestOne()
    {
        $u = new User(USER::RW);
        $u->fetchUserInfo(10);
        $this->assertEquals("fitzy@yahoo.com", $u->email);
        $u->email = "fitz@yahoo.com";
        $this->assertEquals("fitz@yahoo.com", $u->email);
        $u->save();

        $nu = new User(User::RO);
        $nu->fetchUserInfo(10);
        $this->assertEquals("fitz@yahoo.com", $nu->email);
    }
}
