<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/7/11
 * Time: 12:41 PM
 * To change this template use File | Settings | File Templates.
 */

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\IncidentSeverity;

/**
 * @group Severity
 */

class IncidentSeverityTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function loadSevTest()
    {
        $s = new IncidentSeverity(IncidentSeverity::RO);
        $s->loadSeverity(1);
        $this->assertEquals(1, $s->getSevId());
        $this->assertEquals('S0', $s->getSevName());
        $this->assertEquals('Highest Severity, drop all and work it!', $s->getSevDesc());
        
    }

    /**
     * @test
     */
    public function badConn()
    {
        try {
            $s = new IncidentSeverity("someConn");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Bad Sev Id
     */
    public function loadBadSevIdTest()
    {
        $s = new IncidentSeverity(IncidentSeverity::RO);
        try {
            $s->loadSeverity(-1);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     */
    public function InvalidSeverityIdTest()
    {
        $s = new IncidentSeverity(IncidentSeverity::RO);
        try {
            $s->loadSeverity(12343234);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     */
    public function getAllSeveritytest()
    {
        $s = new IncidentSeverity();
        $ret = $s->getAllSeverity();
        $this->assertTrue(is_array($ret));
        //print_r($ret);
        $this->assertEquals('S0', $ret[1]);
        $this->assertEquals('S5',$ret[6]);
    }


}
