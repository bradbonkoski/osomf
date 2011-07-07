<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/7/11
 * Time: 12:41 PM
 * To change this template use File | Settings | File Templates.
 */

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\IncidentStatus;

/**
 * @group Status
 */

class IncidentStatusTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function loadSevTest()
    {
        $s = new IncidentStatus(IncidentStatus::RO);
        $s->loadStatus(1);
        $this->assertEquals(1, $s->getStatusId());
        $this->assertEquals('OPEN', $s->getStatusName());
        $this->assertEquals('Incident is Open and Impacting', $s->getStatusDesc());
        $this->assertEquals(1, $s->getStatusOrder());
    }

    /**
     * @test
     */
    public function badConn()
    {
        try {
            $s = new IncidentStatus("someConn");
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
        $s = new IncidentStatus(IncidentStatus::RO);
        try {
            $s->loadStatus(-1);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     */
    public function InvalidStatusIdTest()
    {
        $s = new IncidentStatus(IncidentStatus::RO);
        try {
            $s->loadStatus(12343234);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
