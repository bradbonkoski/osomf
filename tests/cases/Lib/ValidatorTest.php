<?php
 
require_once 'PHPUnit/Framework/TestCase.php';
use \osomf\Validator;

/**
 * Test the Routing Library Class
 *
 * @category    Test
 * @package     lib
 * @group       validator
 * @author      Brad Bonkoski
 * @copyright   Copyright (c) 2011 Fitzers House of Code
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
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
    public function base()
    {
        $v = new Validator(array(Validator::IS_STRING => true));
        $v->validate(1);
        $err = $v->getErrors();
        //print_r($err);
        $this->assertEquals(1, count($err));
        $this->assertEquals(1, $v->errNo);

        $v->validate("hello");
        $ret = $v->getErrors();
        //print_r($ret);
        $this->assertEquals(1, $v->errNo);
        $this->assertEquals(1, count($ret));
    }

    /**
     * @test
     */
    public function ValidatorIsStringGood()
    {
        $v = new Validator(array(Validator::IS_STRING => true));
        $v->validate("hello");
        $this->assertEquals(0, $v->errNo);
        
    }

    /**
     * @test
     */
    public function FirstStrLenTest()
    {
        $v = new Validator(array(Validator::STRLEN => array('min'=>1, 'max'=>10)));
        $v->validate("asdfghjko");
        $this->assertEquals(0, $v->errNo);

        $v->validate("");
        $this->assertEquals(1, $v->errNo);

        $v->validate("sdkfldkforp");
        $this->assertEquals(2, $v->errNo);
    }

    /**
     * @test
     */
    public function BoundryTestsForStrLen()
    {
        $v = new Validator(array(Validator::STRLEN => array()));
        $v->validate("Hello");
        $this->assertEquals(1, $v->errNo);

        $v = null;
        $v = new Validator(array(Validator::STRLEN => array('max' => 11)));
        $v->validate("");
        $this->assertEquals(1, $v->errNo);
    }
}