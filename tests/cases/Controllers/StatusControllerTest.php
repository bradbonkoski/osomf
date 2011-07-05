<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/status.php';

/**
 * @group StatusController
 */

class StatusControllerTest extends PHPUnit_Framework_TestCase
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
        $s = new status("", "autocomplete");
        $s->setTest();
        $ret = $s->autocomplete('term=or');
        //print_r($ret);
        $data = json_decode($ret);
        //print_r($data[0]);
        $this->assertEquals(1, $data[0]->id);
        $this->assertEquals('ordered', $data[0]->value);
        
    }
}