<?php

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\ProjectModel;

/**
 * @group Project
 */

class ProjectTest extends PHPUnit_Framework_TestCase
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
    public function testProjectLoad()
    {
        $p = new ProjectModel(ProjectModel::RO);
        $p->fetchProjInfo(1);
        $this->assertEquals("Game1", $p->projName);
        $this->assertEquals("Game1 Team", $p->projDesc);
        $this->assertEquals("bradb", $p->projOwner->uname);
    }

    /**
     * @test
     * Test Update.. using seed id #4
     */
    public function generalUpdate()
    {
        $p = new ProjectModel(ProjectModel::RW);
        $p->fetchProjInfo(4);
        $this->assertEquals("Game4", $p->projName);
        $p->projName = "Game 4 - Update";
        $p->projDesc = "New Test Description";
        $p->save();

        $ptwo = new ProjectModel(ProjectModel::RO);
        $ptwo->fetchProjInfo(4);
        $this->assertEquals("New Test Description", $ptwo->projDesc);
        $this->assertEquals("Game 4 - Update", $ptwo->projName);
    }

    /**
     * @test
     * Create A new Project
     */
    public function newProject()
    {
        $p = new ProjectModel(ProjectModel::RW);
        $p->projName = "TestNewProj";
        $p->projDesc = "Description for test Project";
        $p->updateOwner(ProjectModel::OWNER_USER, 1);
        $p->save();
    }

    /**
     * @test
     * Exception Testing - Update With bad Validation
     */
    public function BadUpdate()
    {
        $p = new ProjectModel(ProjectModel::RW);
        $p->fetchProjInfo(1);
        $p->projName = '';
        try {
            $p->save();
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
        $p = new ProjectModel(ProjectModel::RW);
        $p->fetchProjInfo(1);
        $p->projDesc = str_repeat('l', 3200);
        try {
            $p->save();
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
            $p = new ProjectModel("Something");
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
        $p = new ProjectModel(ProjectModel::RO);
        try {
            $p->updateOwner("Brad", 1);
        } Catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * bad Project id
     */
    public function badProjectId()
    {
        $p = new ProjectModel(ProjectModel::RO);
        try {
            $p->fetchProjInfo("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");

    }

    /**
     * @test
     * Update Owner Group
     */
    public function updateOwnerGroup()
    {
        $p = new ProjectModel(ProjectModel::RW);
        $p->fetchProjInfo(5);
        $this->assertEquals("group1", $p->projOwner->groupName);
        $p->updateOwner(ProjectModel::OWNER_GROUP, 2);
        $this->assertEquals("oncall1", $p->projOwner->groupName);
        $p->save();

        $ptwo = new ProjectModel(ProjectModel::RO);
        $ptwo->fetchProjInfo(5);
        $this->assertEquals("oncall1", $ptwo->projOwner->groupName);
    }

    /**
     * @test
     * Bad Updates for Owner Group
     */
    public function badOwnerUpdateGroup()
    {
        $p = new ProjectModel(ProjectModel::RW);
        $p->fetchProjInfo(1);
        try {
            $p->updateOwner(ProjectModel::OWNER_GROUP, 100000000);
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
        $p = new ProjectModel(ProjectModel::RW);
        $p->fetchProjInfo(1);
        try {
            $p->updateOwner(ProjectModel::OWNER_USER, 100000000);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
 
