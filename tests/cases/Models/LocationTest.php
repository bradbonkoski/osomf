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
        $this->assertEquals("DCC1", $l->locName);
        $this->assertEquals("First Data Center", $l->locDesc);
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
        $this->assertEquals("DC4", $l->locName);
        $l->locName = "DC4-Update";
        $l->locDesc = "New Test Description";
        $l->save();

        $ltwo = new LocationModel(LocationModel::RO);
        $ltwo->fetchLocInfo(4);
        $this->assertEquals("New Test Description", $ltwo->locDesc);
        $this->assertEquals("DC4-Update", $ltwo->locName);
    }

    /**
     * @test
     * Create A new location
     */
    public function newLoc()
    {
        $l = new LocationModel(LocationModel::RW);
        $l->locName = "TestNewLoc";
        $l->locDesc = "Description for test location";
        $l->updateOwner(LocationModel::OWNER_USER, 1);
        $l->locAddr = "1122 Testing Road";
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
        $l->locName = '';
        try {
            $l->save();
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
 
