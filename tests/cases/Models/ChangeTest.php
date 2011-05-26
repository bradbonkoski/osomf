<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'www/models/ChangeModel.php';

class ChangeTest extends PHPUnit_Framework_TestCase
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
    public function initial()
    {
        $c = new ChangeModel(changeModel::RO);
    }
}
