<?php

require_once 'PHPUnit/Framework/TestCase.php';

use \Routes;

/**
 * Test the Routing Library Class
 *
 * @category    Test
 * @package     lib
 * @group       route 
 * @author      Brad Bonkoski
 * @copyright   Copyright (c) 2011 Fitzer's House of Code
 */
class RoutingLbTest extends PHPUnit_Framework_TestCase
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
    public function base()
    {
        $rl = new Routes("www.osomf.com/osomf/controller/action/params/something");
        $cont = $rl->getController();
        $this->assertEquals("controller", $cont);
        $this->assertEquals("action", $rl->getAction());
        $p = $rl->getParams();
        //print_r($p);
        $pArr = explode("/", $p);
        $this->assertEquals(2, count($pArr));
    }

    /**
     * @return void
     * @test
     */
    public function ac()
    {
        $rl = new Routes("www.home.com/osomf/status/autocomplete?term=12343");
        $cont = $rl->getController();
        $this->assertEquals("status", $cont);
        //echo "Controller is: $cont\n";
        $action = $rl->getAction();
        $this->assertEquals("autocomplete", $action);
//        echo "Action is: $action\n";
//        print_r($rl->getParams());
        
    }

    /**
     * @test
     * Route Function with greater than 9 for the "id"
     */
    public function bigId()
    {
        $rl = new Routes("www.home.com/osomf/user/view/22/help");
        $this->assertEquals("user", $rl->getController());
        $this->assertEquals("view", $rl->getAction());
        echo $rl->getParams();
        echo "\n";
    }

    /**
     * @return closure
     * @test
     * @group routeWS
     */
    public function getReqWebService()
    {
        $rl = new Routes("localhost/osomf/ws/user/view/1");
        echo "Controller is: ".$rl->getController()."\n";
        echo "Action is: ".$rl->getAction()."\n";
        echo "Params: ".$rl->getParams()."\n";
    }

    /**
     * @test
     */
    public function searchRoutingTest()
    {
        $rl = new Routes('localhost/osomf/search/search');
        $this->assertEquals('search', $rl->getController());
        $this->assertEquals('search', $rl->getAction());
    }
}
