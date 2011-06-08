<?php

require_once 'PHPUnit/Framework/TestCase.php';

//require_once 'lib/Routes.php';
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
}
