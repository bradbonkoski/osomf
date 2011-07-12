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

    /**
     * @test
     */
    public function updateIncidentTest()
    {
        $i = new IncidentModel(IncidentModel::RW);
        $i->loadIncident(2);
        $this->assertEquals('Test Incident #2', $i->getTitle());
        $i->setTitle("Update to Incident #2");
        $i->save();
    }

    /**
     * @test
     */
    public function newIncidentCreationTest()
    {
        $i = new IncidentModel(IncidentModel::RW);
        $i->setTitle("Automated Test Creation #1");
        $i->setStatus(1);
        $i->setStartTime('2011-01-01 12:00:00');
        $i->setCreatedBy(1);
        $i->setSeverity(2);
        $i->setImpact("Automated Impact");
        $i->setRevImpact('Automated Revenue Impact');
        $i->setDescription('Some Descriptive text should go here');
        $i->save();

        $incidentId = $i->getIncidentId();
        $i = null;
        $i = new IncidentModel(IncidentModel::RO);
        $i->loadIncident($incidentId);
        $this->assertEquals('Automated Test Creation #1', $i->getTitle());
        $this->assertEquals('Automated Impact', $i->getImpact());
    }
}