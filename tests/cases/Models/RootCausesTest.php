<?php

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\RootCause;
/**
 * @group RootCause
 */

class RootCausesTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function RCBadConnectionTest()
    {
        try {
            $rc = new RootCause("readme");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     */
    public function RCBadRootCauseIdLoad()
    {
        $rc = new RootCause();
        try {
            $rc->loadRootCause(12033948832);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     */
    public function RCBadRootCasueIdStringOnLoad()
    {
        $rc = new RootCause();
        try {
            $rc->loadRootCause("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     */
    public function loadRootCause()
    {
        $rc = new RootCause();
        $rc->loadRootCause(1);
        $this->assertEquals('rootcause', $rc->getCauseType());
        $this->assertEquals('Hardware Failure', $rc->getCauseName());
    }

}
