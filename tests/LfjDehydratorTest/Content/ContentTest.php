<?php

namespace LfjDehydratorTest\Dehydrator;

use Lfj\Dehydrator\Content\Content;
use Zend\Uri\Uri;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function stringPassedToConstructorIsEqualToGetString()
    {
        /*
         * create a new Content object
         * pass a string
         * check if ::getString() returns the same string
         */

        $expected = '<html></html>';
        $content = new Content('<html></html>');

        $result = $content->toString();

        $this->assertEquals($expected, $result);
    }
}
