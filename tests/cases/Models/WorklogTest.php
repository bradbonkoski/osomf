<?php

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\Worklog;

/**
 * @group Worklog
 */

class WorklogTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function addStatusEntry()
    {
        $wl = new Worklog(Worklog::RW);
        $data = array('orig' => '1', 'new' => '2', 'reason' => "Testing, that's all");
        $wl->newWorkLog(1, 1, Worklog::TYPE_STATUS, $data);
        $wl->save();
    }

    /**
     * @return void
     * @test
     */
    public function getWorklogEntry()
    {
        $wl = new Worklog(Worklog::RO);
        $wl->getWlEntry(2);
        //print_r($wl->getData());
        $this->assertEquals(2, $wl->getIncidentId());
        $this->assertEquals(1, $wl->getUserId());
        $this->assertEquals('STATUS', $wl->getType());
        $data = $wl->getData();
        $this->assertEquals('OPEN', $data[0]);
        $this->assertEquals('ESCALATED', $data[1]);
        $this->assertTrue(is_array($wl->getData()));
        $this->assertEquals('Testing thats all', $data[2]);
    }
}
 
