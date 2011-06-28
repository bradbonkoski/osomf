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

    /**
     * @test
     * Bad Connection Descriptior
     */
    public function badConnector()
    {
        try {
            $a = new AssetModel("connection");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Test Bulk of the Getter Methods
     */
    public function getterTestsOne()
    {
        $a = new AssetModel(AssetModel::RO);
        $a->loadAsset(1);
        $this->assertEquals(1, $a->getAssetId());

        $this->assertEquals("USER", $a->getOwnerType());
        $this->assertEquals(1, $a->getOwnerId());
        $times = $a->getAssetTimes();
        $this->assertTrue(count($times)> 0 );
    }

    /**
     * @test
     * New Asset Add
     */
    public function addNewAssetOne()
    {
        $a = new AssetModel(AssetModel::RW);
        $a->ciName = "test1.home.com";
        $a->ciDesc = "Automated Test Asset";
        $a->updateOwner(AssetModel::OWNER_GROUP, 1);
        $a->updateProject(3);
        $a->updateStatus(6);
        $a->updateType(3);
        $a->updatePhyParent(1);
        $a->updateNetParent(2);
        $a->updateLoc(4);
        $a->save();
    }

    /**
     * @test
     * Verify CI with Bad ciid
     */
    public function VerifyWithBadCIIID()
    {
        $a = new AssetModel(AssetModel::RO);
        $this->assertTrue(!$a->verifyCi(11304932023));
        $this->assertTrue($a->verifyCi(1));
    }

    /**
     * @test
     * Test Update Project Exceptions - 1
     */
    public function projUpdateExceptions()
    {
        $a = new AssetModel(AssetModel::RO);
        $a->loadAsset(1);
        try {
            $a->updateProject("string");
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exceptions");
    }

    /**
     * @test
     * Test Update Project Exceptions - 2
     */
    public function projUpdateExceptionTwo()
    {
        $a = new AssetModel(AssetModel::RO);
        $a->loadAsset(1);
        try {
            $a->updateProject(112343234);
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }

    /**
     * @test
     * Test for listAssets method
     */
    public function testlistassets()
    {
        $a = new AssetModel(AssetModel::RO);
        $data = $a->listAssets();
        $this->assertTrue(count($data) > 0 ) ;
    }
}
 
