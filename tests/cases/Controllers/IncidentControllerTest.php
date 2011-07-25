<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 12:31 PM
 * To change this template use File | Settings | File Templates.
 */
 
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/incident.php';

/**
 * @group IncidentController
 */

class IncidentControllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function initial()
    {
        $c = new incident("", "view");
        $c->setTest();
        $c->view("1");

        //print_r($c->data);
        $this->assertEquals(1, $c->data['incidentId']);
        $this->assertEquals('OPEN', $c->data['status']);
        $this->assertTrue(is_array($c->data['statusVals']));
        $this->assertTrue(is_array($c->data['sevValues']));
    }

    
}