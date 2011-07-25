<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/8/11
 * Time: 12:31 PM
 * To change this template use File | Settings | File Templates.
 */
 
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'www/controllers/search.php';

/**
 * @group SearchController
 */

class SearchControllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function incidentSearch()
    {
        $c = new search("", "incident");
        $c->setTest();
        $_POST['btnSearch'] = '1';
        $_POST['title'] = "#3";
        $c->incident();

        //print_r($c->data);
        $this->assertTrue(is_array($c->data['cols']));
        $this->assertEquals(3, $c->data['searchResults'][0]['incidentId']);
        $this->assertEquals(1, $c->data['results']);
    }

    /**
     * @test
     */
    public function AssetSearch()
    {
        $c = new search("", "asset");
        $c->setTest();
        $_POST['btnSearch'] = 1;
        $_POST['ciName'] = 'ci1';
        $c->asset();

        //print_r($c->data);
        $this->assertTrue(is_array($c->data['cols']));
        $this->assertEquals(1, $c->data['results']);
        $this->assertEquals(1, $c->data['searchResults'][0]['ciid']);
    }
    
}