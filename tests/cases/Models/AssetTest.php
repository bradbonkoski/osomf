<?php

require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\models\AssetModel;

/**
 * @group Asset
 */

class AssetTest extends PHPUnit_Framework_TestCase
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
    public function holder()
    {
        $a = new AssetModel(AssetModel::RO);
        $a->loadAsset(1);
        $this->assertEquals('bradb', $a->owner->uname);
        $this->assertEquals('Game1', $a->project->projName);
        $this->assertEquals('prod', $a->ciStatus->statusName);
        $this->assertEquals('Virtual', $a->ciType->typeName);
        $this->assertEquals('ci1.home.com', $a->ciName);
        $this->assertEquals('Virtual CI for home.com', $a->ciDesc);
    }
}
 
