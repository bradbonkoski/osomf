<?php


require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\IncidentModel;

/**
 * @group Incident
 */

class IncidentModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function initalLoadTest()
    {
        $i = new IncidentModel(IncidentModel::RO);
        $i->loadIncident(1);
        $this->assertEquals(1, $i->getIncidentId());
        $this->assertEquals('Test Incident #1', $i->getTitle());
        $this->assertEquals(1, $i->getCreatedById());
        $this->assertEquals(1, $i->getSeverityId());
        $this->assertEquals('Not sure on the impact', $i->getImpact());
        $this->assertEquals('unknown', $i->getRevImpact());
        $this->assertEquals('cat pulled out the power cord', $i->getDescription());
        $this->assertEquals('bradb', $i->createdByUser->uname);
        $this->assertEquals('S0', $i->severity->getSevName());
    }
}