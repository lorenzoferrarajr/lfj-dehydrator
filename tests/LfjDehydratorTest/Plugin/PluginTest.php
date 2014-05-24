<?php

namespace LfjDehydratorTest\Reader;

use Lfj\Dehydrator\Content\Content;
use Zend\Uri\Uri;

class PluginTest extends \PHPUnit_Framework_TestCase
{
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
