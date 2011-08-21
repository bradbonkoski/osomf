<?php


require_once 'PHPUnit/Framework/TestCase.php';

use osomf\models\Tagging;

/**
 * @group Tagging
 */

class TaggingTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getTagsByOwnerTest()
    {
        $t = new Tagging();
        $t->getTagsByOwner(1);
        $this->assertTrue(is_array($t->tags));
        $this->assertEquals('Tag1', $t->tags[1]);
        $this->assertEquals('tag2', $t->tags[2]);
        
    }

    /**
     * @test
     */
    public function addTagTest()
    {
        $t = new Tagging(Tagging::RW);
        $t->createTag(10, 'AutomatedTestTag');

        $t->getTagsByOwner(10);
        $this->assertEquals(1, count($t->tags));

        try {
            $t->createTag(10, 'AutomatedTestTag');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Missed Expected Exception");
    }
}
 
