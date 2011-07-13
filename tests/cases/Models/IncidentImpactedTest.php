<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/12/11
 * Time: 2:48 PM
 * To change this template use File | Settings | File Templates.
 */
 
require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\IncidentImpact;

/**
 * @group IncidentImpact
 */

class IncidentImpactedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testNewImpactForIncident()
    {
        $ii = new IncidentImpact(IncidentImpact::RW);
        $ii->setIncidentId(1);
        $ii->setImpactType(IncidentImpact::IMPACT_CI);
        $ii->setImpactVal(1);
        $ii->setImpactDesc('node taken down');
        $ii->setImpactSeverity(1);
        $ret = $ii->save();
        print_r($ret);
        echo "Impact id is: ".$ii->getImpactId()."\n";
    }

    /**
     * @test
     */
    public function getImpactedData()
    {
        $ii = new IncidentImpact();
        $ii->loadImpacted(1);
        $this->assertEquals(1, $ii->getIncidentId());
        $this->assertEquals('asset', $ii->getImpactType());
        $this->assertEquals(1, $ii->getImpactValId());
        $this->assertEquals('Machine is down!', $ii->getImpactDesc());
        $this->assertEquals(1, $ii->getImpactSeverity());
    }

    /**
     * @test
     * @group IncidentImpactUpdate
     */
    public function IncidentImpactedUpdateTest()
    {
        $ii = new IncidentImpact(IncidentImpact::RW);
        $ii->loadImpacted(2);
        $this->assertEquals(2, $ii->getImpactSeverity());
        $ii->setImpactSeverity(3);
        $ii->setImpactDesc('test');
        $ret = $ii->save();
        print_r($ret);

        $ii = null;
        $ii = new IncidentImpact();
        $ii->loadImpacted(2);
        $this->assertEquals(3, $ii->getImpactSeverity());
        $this->assertEquals('test', $ii->getImpactDesc());
    }
}