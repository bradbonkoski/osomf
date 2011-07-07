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

    /**
     * @test
     */
    public function UserAutoComplete()
    {
        $u = new user("", "autocomplete");
        $u->setTest();
        $ret = $u->autocomplete('term=br');
        //print_r($ret);
        $data = json_decode($ret);
        //print_r($data[0]);
        $this->assertEquals(3, $data[0]->id);
        $this->assertEquals('brad2', $data[0]->value);

        $this->assertEquals(1, $data[1]->id);
        $this->assertEquals('bradb', $data[1]->value);
    }

    /**
     * @test
     */
    public function UserHome()
    {
        $u = new user('','home');
        $u->setTest();
        $u->home(array());
        //print_r($u->data);
        $this->assertTrue(is_array($u->data['users']));
        $this->assertEquals('1', $u->data['users'][0]['userId']);
        $this->assertEquals('bradb', $u->data['users'][0]['uname']);
    }
}