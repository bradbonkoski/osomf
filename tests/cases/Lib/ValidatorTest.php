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

    /**
     * @test
     */
    public function NumGroupTest()
    {
        $v = new Validator(array(
                Validator::IS_NUM => true,
                Validator::NUM_RANGE => array('min' => 10, 'max' => 100)
                           ));
        $v->validate(10);
        $this->assertEquals(0, $v->errNo);

        $v->validate(100);
        $this->assertEquals(0, $v->errNo);
        
        $v->validate(9);
        $this->assertEquals(1, $v->errNo);

        $v->validate(101);
        $this->assertEquals(2, $v->errNo);

        $v = new Validator(array(Validator::IS_NUM => true));
        $v->validate("string");
        $this->assertEquals(1, $v->errNo);

        $v = new Validator(array(Validator::NUM_RANGE => array()));
        $v->validate(10);
        $this->assertEquals(1, $v->errNo);
    }

    /**
     * @test
     */
    public function phoneTest()
    {
        $v = new Validator(array(Validator::IS_PHONE => true));
        $v->validate("(800) 333-4443");
        print_r($v->getErrors());
        $this->assertEquals(0, $v->errNo);

        $v = new Validator(array(Validator::IS_PHONE => true));
        $v->validate("+1 800-333-4443");
        $this->assertEquals(0, $v->errNo);

        $v = new Validator(array(Validator::IS_PHONE => true));
        $v->validate("444-3223-2123");
        $this->assertEquals(1, $v->errNo);
    }

    /**
     * Test for String which has a number in it.  (should be valid!)
     * @test
     */
    public function stringWithNumber()
    {
        $v = new Validator(array(Validator::IS_STRING => true));
        $v->validate("Hello1");
        $this->assertEquals(0, $v->errNo);
    }
}