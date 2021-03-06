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

    /**
     * @test
     */
    public function IncidentControllerEditTest()
    {
        $c = new incident('', 'edit');
        $c->setTest();
        $c->edit("5");

        //print_r($c->data);
        $this->assertTrue(is_array($c->data['sevValues']));
        $this->assertTrue(is_array($c->data['impacts']));
        $this->assertTrue(is_array($c->data['worklogs']));

        $_POST['subIncident'] =1;
        $upTitle = 'incident controller test updated title';
        $_POST['title'] = $upTitle;
        $_POST['desc'] = $upTitle;
        $_POST['impact'] = $upTitle;
        $_POST['revImpact'] = $upTitle;
        $_POST['resolveSteps'] = $upTitle;

        $c->edit("5");
        $this->assertEquals($upTitle, $c->data['incidentTitle']);
        $this->assertEquals($upTitle, $c->data['desc']);
        $this->assertEquals($upTitle, $c->data['impact']);
        $this->assertEquals($upTitle, $c->data['revImpact']);
        $this->assertEquals($upTitle, $c->data['resolveSteps']);
        //print_r($c->data);
    }
    
}