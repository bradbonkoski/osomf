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
        $this->assertEquals('2010-02-01 12:30:00', $i->getResolveTime());
        $this->assertEquals('some steps taken', $i->getResolveSteps());
        $this->assertEquals('2010-02-01 11:00:00', $i->getStartTime());
        $this->assertEquals('1', $i->getStatusId());
        $this->assertEquals(1, $i->getUpdatedById());
        $this->assertEquals('2010-02-01 10:55:00', $i->getDetectTime());
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

    /**
     * @test
     */
    public function listHomePageIncidentsTest()
    {
        $i = new IncidentModel();
        $ret = $i->listHomeIncidents();
        $this->assertTrue(is_array($ret));
        //print_r($ret);
        $this->assertEquals('1', $ret[0]['incidentId']);
        $this->assertEquals('OPEN', $ret[0]['status']);
        $this->assertEquals('S0', $ret[0]['severity']);
    }

    /**
     * @test
     * @group b12
     */
    public function addIncidentImpactTest()
    {
        $i = new IncidentModel(IncidentModel::RW);
        $i->loadIncident(3);
        $vals = $i->addImpact(
            1,
            'asset',
            1,
            'machine is down',
            1
        );
        //print_r($vals);
        $this->assertTrue(is_array($vals));
        $this->assertEquals('ci1.home.com', $vals['name']);
        $this->assertEquals('S0', $vals['sev']);
    }

    /**
     * @test
     * @group b12
     */
    public function AddImpactInvalidImpactTest()
    {
        $i = new IncidentModel(IncidentModel::RW);
        try {
            $i->addImpact(1, 'car', 1, 'notta',1);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail('Missed Expected Exception');
    }

    /**
     * @test
     * @group b12
     */
    public function AddImpactInvalidImpactValue()
    {
        $i = new IncidentModel(IncidentModel::RW);
        try {
            $i->addImpact(1, 'asset', 'string', 'notta', 1);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * @group b12
     */
    public function AddImpactInvalidSeveirytId()
    {
        $i = new IncidentModel(IncidentModel::RW);
        try {
            $i->addImpact(1, 'asset', 1, 'notta', 'S0');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * @group b123
     */
    public function invalidSeverityTest()
    {
        $i = new IncidentModel(IncidentModel::RW);
        try {
            $i->addImpact(1, 'asset', 1, 'notta', 120394322);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}