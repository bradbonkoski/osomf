<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/project.php';

/**
 * @group ProjectController
 */

class ProjectControllerTest extends PHPUnit_Framework_TestCase
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
    public function ProjAutoComplete()
    {
        $p = new project("", "autocomplete");
        $p->setTest();
        $ret = $p->autocomplete('term=Gam');
        //print_r($ret);
        $data = json_decode($ret);
        //print_r($data[0]);
        $this->assertEquals(1, $data[0]->id);
        $this->assertEquals('Game1', $data[0]->value);

        $this->assertEquals(2, $data[1]->id);
        $this->assertEquals('Game2', $data[1]->value);
        
    }

    /**
     * @test
     */
    public function ProjectView()
    {
        $p = new project("", "view");
        $p->setTest();
        $p->view("1");
        //print_r($p->data);
        $this->assertEquals("Project Data for: Game1", $p->data['title']);
        $this->assertEquals("Game1", $p->data['projName']);
        $this->assertEquals("Game1 Team", $p->data['projDesc']);

    }
}