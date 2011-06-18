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
        $this->assertTrue(true);
    }
}
 
