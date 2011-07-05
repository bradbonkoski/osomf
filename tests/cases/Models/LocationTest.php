<?php

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\LocationModel;

/**
 * @group Location
 */

class LocationTest extends PHPUnit_Framework_TestCase
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
    public function testLocationLoad()
    {
        $l = new LocationModel(LocationModel::RO);
        $l->fetchLocInfo(1);
        $this->assertEquals("DCC1", $l->getLocName());
        $this->assertEquals("First Data Center", $l->getLocDesc());
        //print_r($l->locOwner);
        $this->assertEquals("bradb", $l->locOwner->uname);
    }

    /**
     * @test
     * Test Update.. using seed id #4
     */
    public function generalUpdate()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo(4);
        $this->assertEquals("DC4", $l->getLocName());
        $l->setLocName("DC4-Update");
        $l->setLocDesc("New Test Description");
        $l->save();

        $ltwo = new LocationModel(LocationModel::RO);
        $ltwo->fetchLocInfo(4);
        $this->assertEquals("New Test Description", $ltwo->getLocDesc());
        $this->assertEquals("DC4-Update", $ltwo->getLocName());
    }

    /**
     * @test
     * Create A new location
     */
    public function newLoc()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->setLocName("TestNewLoc");
        $l->setLocDesc("Description for test location");
        $l->updateOwner(LocationModel::OWNER_USER, 1);
        $l->setLocAddr("1122 Testing Road");
        $l->save();
    }

    /**
     * @test
     * Exception Testing - Update With bad Validation
     */
    public function BadUpdate()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo(1);
        $l->setLocName('');
        try {
            $l->save();
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Exception Testing Phase 2 - Update with Bad Validation
     */
    public function BadUpdatePhaseTwo()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo(1);
        $l->setLocDesc(str_repeat('l', 3200));
        try {
            $l->save();
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Passe Invlaid Connector
     */
    public function InvalidConn()
    {
        try {
            $l = new LocationModel("Something");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Bad Owner Type for update Owner
     */
    public function BadOwnerType()
    {
        $l = new LocationModel(LocationModel::RO);
        try {
            $l->updateOwner("Brad", 1);
        } Catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * bad Loc id
     */
    public function badLocationId()
    {
        $l = new LocationModel(LocationModel::RO);
        try {
            $l->fetchLocInfo("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");

    }

    /**
     * @test
     * @group UpdateLocGroupOwner
     * Update Owner Group
     */
    public function updateOwnerGroup()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo(5);
        $this->assertEquals("group1", $l->locOwner->groupName);
        $l->updateOwner(LocationModel::OWNER_GROUP, 2);
        $this->assertEquals("oncall1", $l->locOwner->groupName);
        $l->save();

        $ltwo = new LocationModel(LocationModel::RO);
       $ltwo->fetchLocInfo(5);
        $this->assertEquals("oncall1", $ltwo->locOwner->groupName);
    }

    /**
     * @test
     * Bad Updates for Owner Group
     */
    public function badOwnerUpdateGroup()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo(1);
        try {
            $l->updateOwner(LocationModel::OWNER_GROUP, 100000000);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

     /**
     * @test
     * Bad Updates for Owner Group
     */
    public function badOwnerUpdateUser()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->fetchLocInfo(1);
        try {
            $l->updateOwner(LocationModel::OWNER_USER, 100000000);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
 
