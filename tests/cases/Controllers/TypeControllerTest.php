<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/type.php';

/**
 * @group TypeController
 */

class TypeControllerTest extends PHPUnit_Framework_TestCase
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
        $t = new type("", "autocomplete");
        $t->setTest();
        $ret = $t->autocomplete('term=Virt');
        //print_r($ret);
        $data = json_decode($ret);
        //print_r($data[0]);
        $this->assertEquals(3, $data[0]->id);
        $this->assertEquals('Virtual', $data[0]->value);
        
    }
}