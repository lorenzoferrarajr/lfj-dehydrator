<?php

namespace LfjDehydratorTest\Reader;

use Lfj\Dehydrator\Content\Content;
use Zend\Uri\Uri;

class PluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constructorCorrectlyHandlesPassedParameters()
    {
        /*
         * create a new Uri object
         * create a new Content object
         * create a new PluginHelper object
         * pass the Uri and Content object to the PluginHelper constructor
         * check that ::getUrl() returns the same Uri object passed to the constructor
         * check that ::getContent() returns the same Content object passed to the constructor
         */

        $uri = new Uri('http://www.example.com/');
        $content = new Content('<html></html>');

        $plugin = new \LfjDehydratorTest\HelperClass\PluginHelper($uri, $content);

        $this->assertSame($uri, $plugin->getUrl());
        $this->assertSame($content, $plugin->getContent());
    }

    /**
     * @test
     */
    public function returnedResultReturnsExpectedValue()
    {
        /*
         * create a new Plugin
         * call ::setResult() passing some data
         * check if ::getResult() returns expected value
         */

        $uri = new Uri('http://www.example.com/');
        $content = new Content('<html></html>');

        $plugin = new \LfjDehydratorTest\HelperClass\EmptyPluginHelper($uri, $content);

        $plugin->setResult(array('result' => 'data'));

        $expected = array('result' => 'data');
        $this->assertEquals($expected, $plugin->getResult());
    }
}
