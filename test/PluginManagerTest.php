<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZFTest\Rest;

use PHPUnit_Framework_TestCase as TestCase;
use ZFTest\Rest\TestAsset\TestResourceListener;
use ZFTest\Rest\Plugin\TestAsset\SamplePlugin;
use ZFTest\Rest\Plugin\TestAsset\SamplePluginWithConstructor;
use ZF\Rest\PluginManager;
use Zend\ServiceManager\ServiceManager;

class PluginManagerTest extends TestCase
{
    public function testPluginManagerThrowsExceptionForMissingPluginInterface()
    {
        $this->setExpectedException('ZF\Rest\Exception\InvalidPluginException');

        $pluginManager = new PluginManager;
        $pluginManager->setInvokableClass('samplePlugin', 'stdClass');

        $plugin = $pluginManager->get('samplePlugin');
    }

    public function testPluginManagerInjectsResourceListenerInPlugin()
    {
        $resourceListener    = new TestResourceListener(new \stdClass);
        $pluginManager = new PluginManager;
        $pluginManager->setInvokableClass('samplePlugin', 'ZFTest\Rest\Plugin\TestAsset\SamplePlugin');
        $pluginManager->setResourceListener($resourceListener);

        $plugin = $pluginManager->get('samplePlugin');
        $this->assertEquals($resourceListener, $plugin->getResource());
    }

    public function testPluginManagerInjectsResourceListenerForExistingPlugin()
    {
        $resourceListener1   = new TestResourceListener(new \stdClass);
        $pluginManager = new PluginManager;
        $pluginManager->setInvokableClass('samplePlugin', 'ZFTest\Rest\Plugin\TestAsset\SamplePlugin');
        $pluginManager->setResourceListener($resourceListener1);

        // Plugin manager registers now instance of SamplePlugin
        $pluginManager->get('samplePlugin');

        $resourceListener2   = new TestResourceListener(new \stdClass);
        $pluginManager->setResourceListener($resourceListener2);

        $plugin = $pluginManager->get('samplePlugin');
        $this->assertEquals($resourceListener2, $plugin->getResource());
    }

    public function testGetWithConstrutor()
    {
        $pluginManager = new PluginManager;
        $pluginManager->setInvokableClass('samplePlugin', 'ZFTest\Rest\Plugin\TestAsset\SamplePluginWithConstructor');
        $plugin = $pluginManager->get('samplePlugin');
        $this->assertEquals($plugin->getBar(), 'baz');
    }

    public function testGetWithConstrutorAndOptions()
    {
        $pluginManager = new PluginManager;
        $pluginManager->setInvokableClass('samplePlugin', 'ZFTest\Rest\Plugin\TestAsset\SamplePluginWithConstructor');
        $plugin = $pluginManager->get('samplePlugin', 'foo');
        $this->assertEquals($plugin->getBar(), 'foo');
    }

    public function testCanCreateByFactory()
    {
        $pluginManager = new PluginManager;
        $pluginManager->setFactory('samplePlugin', 'ZFTest\Rest\Plugin\TestAsset\SamplePluginFactory');
        $plugin = $pluginManager->get('samplePlugin');
        $this->assertInstanceOf('\ZFTest\Rest\Plugin\TestAsset\SamplePlugin', $plugin);
    }

    public function testCanCreateByFactoryWithConstrutor()
    {
        $pluginManager = new PluginManager;
        $pluginManager->setFactory('samplePlugin', 'ZFTest\Rest\Plugin\TestAsset\SamplePluginWithConstructorFactory');
        $plugin = $pluginManager->get('samplePlugin', 'foo');
        $this->assertInstanceOf('\ZFTest\Rest\Plugin\TestAsset\SamplePluginWithConstructor', $plugin);
        $this->assertEquals($plugin->getBar(), 'foo');
    }
}