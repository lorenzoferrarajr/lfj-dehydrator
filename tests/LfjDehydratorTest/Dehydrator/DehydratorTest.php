<?php

namespace LfjDehydratorTest\Dehydrator;

use Lfj\Dehydrator\Content\Content;
use Lfj\Dehydrator\Dehydrator;
use Zend\Uri\Uri;

class LfjDehydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constructorInitializesCorrectlyPlugins()
    {
        /*
         * create a new Dehydrator object
         * check if ::plugins is an ArrayIterator
         */

        $dehydrator = new Dehydrator();

        $class = new \ReflectionClass($dehydrator);
        $property = $class->getProperty('plugins');
        $property->setAccessible(true);
        $result = $property->getValue($dehydrator);

        $this->assertEquals(array(), $result);
    }

    /**
     * @test
     */
    public function constructorInitializesCorrectlyResult()
    {
        /*
         * create a new Dehydrator object
         * check if ::result is an ArrayIterator
         */

        $dehydrator = new Dehydrator();

        $class = new \ReflectionClass($dehydrator);
        $property = $class->getProperty('result');
        $property->setAccessible(true);
        $result = $property->getValue($dehydrator);

        $this->assertEquals(array(), $result);
    }

    /**
     * @test
     */
    public function getResultReturnsExpectedValue()
    {
        /*
         * create a new Dehydrator object
         * set the ::result property to an empty ArrayIterator object
         * check if ::getResult() returns the same object
         */

        $expected = array();

        $dehydrator = new Dehydrator();

        $class = new \ReflectionClass($dehydrator);

        $property = $class->getProperty('result');
        $property->setAccessible(true);
        $property->setValue($dehydrator, $expected);

        $result = $dehydrator->getResult();

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function addedPluginsAreCorrectlyHandled()
    {
        /*
         * plugin1 is not valid
         * plugin2 is valid
         * create a new Dehydrator object
         * plugin1 and plugin2 get added to the Dehydrator object
         * check that ::addPlugin() returns false for plugin1
         * check that ::addPlugin() returns true for plugin2
         * check that ::plugins contains only plugin2
         */

        $plugin1 = "DoesNotImplementPluginInterface";

        $plugin2 = $this->getMockBuilder('LfjDehydratorTest\HelperClass\PluginHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $dehydrator = new Dehydrator();

        $class = new \ReflectionClass($dehydrator);

        $property = $class->getProperty('plugins');
        $property->setAccessible(true);

        $method = $class->getMethod('addPlugin');

        $result1 = $method->invoke($dehydrator, $plugin1);
        $this->assertFalse($result1);

        $result2 = $method->invoke($dehydrator, get_class($plugin2));
        $this->assertTrue($result2);

        /** @var \ArrayIterator $result */
        $result = $property->getValue($dehydrator);

        $expected = array(
            array(
                'plugin' => get_class($plugin2),
                'type' => null
            )
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function runPluginAlwaysGetsExpectedResult()
    {
        /*
         * four plugins get processed by ::runPlugin()
         * each time the result is checked
         */

        /*
         * mock and check plugin1
         */

        $plugin1 = $this->getMockBuilder('LfjDehydratorTest\HelperClass\EmptyPluginHelper')
            ->setMethods(array('getKey', 'isEnabled', 'run', 'getResult'))
            ->disableOriginalConstructor()
            ->getMock();

        $plugin1->expects($this->once())
            ->method('getKey')
            ->will($this->returnValue('test-plugin'));

        $plugin1->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        $plugin1->expects($this->once())
            ->method('run');

        $plugin1->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array('result')));

        $dehydrator = new Dehydrator();

        $dehydrator->runPlugin($plugin1, null);

        $expected = array();
        $expected['test-plugin'] = array('result');

        $this->assertEquals($expected, $dehydrator->getResult());

        /*
         * mock and check plugin2
         */

        $plugin2 = $this->getMockBuilder('LfjDehydratorTest\HelperClass\EmptyPluginHelper')
            ->setMethods(array('getKey', 'isEnabled', 'run', 'getResult'))
            ->disableOriginalConstructor()
            ->getMock();

        $plugin2->expects($this->once())
            ->method('getKey')
            ->will($this->returnValue('test-plugin'));

        $plugin2->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        $plugin2->expects($this->once())
            ->method('run');

        $plugin2->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array('result')));

        $dehydrator->runPlugin($plugin2, null);

        $expected = array();
        $expected['test-plugin'] = array('result', 'result');

        $this->assertEquals($expected, $dehydrator->getResult());

        /*
         * mock and check plugin3
         */

        $plugin3 = $this->getMockBuilder('LfjDehydratorTest\HelperClass\PluginHelper')
            ->setMethods(array('getKey', 'isEnabled', 'run', 'getResult'))
            ->disableOriginalConstructor()
            ->getMock();

        $plugin3->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        $plugin3->expects($this->once())
            ->method('getKey')
            ->will($this->returnValue('test-plugin-replaceable'));

        $plugin3->expects($this->once())
            ->method('run');

        $plugin3->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array('result')));

        $dehydrator->runPlugin($plugin3, 'replaceable');

        $expected['test-plugin-replaceable'] = array('result');

        $this->assertEquals($expected, $dehydrator->getResult());

        /*
         * mock and check plugin4
         */

        $plugin4 = $this->getMockBuilder('LfjDehydratorTest\HelperClass\PluginHelper')
            ->setMethods(array('getKey', 'isEnabled', 'run', 'getResult'))
            ->disableOriginalConstructor()
            ->getMock();

        $plugin4->expects($this->any())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        $plugin4->expects($this->once())
            ->method('getKey')
            ->will($this->returnValue('test-plugin-replaceable'));

        $plugin4->expects($this->once())
            ->method('run');

        $plugin4->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(array('result')));

        $dehydrator->runPlugin($plugin4, 'replaceable');

        $this->assertEquals($expected, $dehydrator->getResult());
    }

    /**
     * @test
     */
    public function notAddingPluginsGivesCorrectResult()
    {
        /*
         * create a Uri object
         * create a Content object
         * create a Dehydrator object
         * get the result from ::dehydrate()
         * check if the result is correct
         */

        $expected = array();

        $url = New Uri('http://example.com/');
        $content = new Content('');

        $dehydrator = new Dehydrator();

        $result = $dehydrator->dehydrate($url, $content)->getResult();

        $this->assertEquals($expected, $result);

    }

    public function pluginDataProvider()
    {
        return array(
            array('LfjDehydratorTest\HelperClass\PluginHelper', 'test-plugin', array('result')),
            array('LfjDehydratorTest\HelperClass\PluginHelper', 'test-plugin', array('result')),
        );
    }

    /**
     * @dataProvider pluginDataProvider
     * @test
     */
    public function addingSinglePluginGivesCorrectResult($plugin, $expectedKey, $expectedValue)
    {
        /*
         * create a Uri object
         * create a Content object
         * create a Dehydrator
         * add a plugin
         * check if the result of ::dehydrate() contains an index corresponding to the plugin key
         * check if the result of ::dehydrate() is what the added plugin returns
         */

        $url = New Uri('http://example.com/');
        $content = new Content('');

        $dehydrator = new Dehydrator();

        $dehydrator->addPlugin($plugin);

        $result = $dehydrator->dehydrate($url, $content)->getResult();

        $expected = array();
        $expected[$expectedKey] = $expectedValue;

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function addingMultipleReplaceablePluginsGivesCorrectResult()
    {
        /*
         * create a new Uri object
         * create a new Content object
         * create a new Dehydrator object
         * add two plugins implementing ReplaceablePluginInterface having the same key
         * check if the result from ::dehydrate() contains an index corresponding to the plugin key
         * check if the result from ::dehydrate() is what the last added plugin returns
         */

        $url = New Uri('http://example.com/');
        $content = new Content('');

        $dehydrator = new Dehydrator();
        $dehydrator->addPlugin('LfjDehydratorTest\HelperClass\PluginHelper', 'replaceable');
        $dehydrator->addPlugin('LfjDehydratorTest\HelperClass\PluginHelper', 'replaceable');

        $result = $dehydrator->dehydrate($url, $content)->getResult();

        $expected = array();
        $expected['test-plugin'] = array('result');

        $this->assertEquals($expected, $result);
    }
}
