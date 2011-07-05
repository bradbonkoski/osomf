<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/location.php';

/**
 * @group LocationController
 */

class LocationControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function LocAutoComplete()
    {
        $l = new location("", "autocomplete");
        $l->setTest();
        $ret = $l->autocomplete('term=DC');
        //print_r($ret);
        $data = json_decode($ret);
        //print_r($data[0]);
        $this->assertEquals(1, $data[0]->id);
        $this->assertEquals('DCC1', $data[0]->value);

        $this->assertEquals(2, $data[1]->id);
        $this->assertEquals('DC2', $data[1]->value);
        
    }

    /**
     * @test
     */
    public function LocView()
    {
        $l = new location("", "view");
        $l->setTest();
        $l->view("1");
        //print_r($l->data);
        $this->assertEquals("Location information for: DCC1", $l->data['title']);
        $this->assertEquals("DCC1", $l->data['locName']);
        $this->assertEquals("First Data Center", $l->data['locDesc']);
        $this->assertEquals("123 Main Street", $l->data['locAddr']);
        $this->assertEquals('1', $l->data['locId']);
        $this->assertTrue(count($l->data['changes']) == 0);

    }
}