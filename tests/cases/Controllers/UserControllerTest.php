<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 12:31 PM
 * To change this template use File | Settings | File Templates.
 */
 
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/user.php';

/**
 * @group UserController
 */

class UserControllerTest extends PHPUnit_Framework_TestCase
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
        $c = new user("", "view");
        $c->setTest();
        $c->view("1");
        //print_r($c->data);
        $this->assertEquals("bradb", $c->data['username']);
        $this->assertEquals("bradley@ymail.com", $c->data['email']);
        $this->assertEquals("800-698-5555", $c->data['phone']);
        
    }
}