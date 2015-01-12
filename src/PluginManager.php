<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\Rest;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * Plugin manager implementation for resources
 */
class PluginManager extends AbstractPluginManager
{
    /**
     * @var AbstractResourceListener
     */
    protected $resourceListener;

    /**
     * Retrieve a registered instance
     *
     * After the plugin is retrieved from the service locator, inject the
     * controller in the plugin every time it is requested. This is required
     * because a controller can use a plugin and another controller can be
     * dispatched afterwards. If this second controller uses the same plugin
     * as the first controller, the reference to the controller inside the
     * plugin is lost.
     *
     * @param  string $name
     * @param  mixed  $options
     * @param  bool   $usePeeringServiceManagers
     * @return mixed
     */
    public function get($name, $options = array(), $usePeeringServiceManagers = true)
    {
        $plugin = parent::get($name, $options, $usePeeringServiceManagers);
        $this->injectResourceListener($plugin);
        return $plugin;
    }

    /**
     * Set controller
     *
     * @param  AbstractResourceListener $resource
     * @return PluginManager
     */
    public function setResourceListener(AbstractResourceListener $resource)
    {
        $this->resourceListener = $resource;

        return $this;
    }

    /**
     * Retrieve controller instance
     *
     * @return null|AbstractResourceListener
     */
    public function getResourceListener()
    {
        return $this->resourceListener;
    }

    /**
     * Inject a helper instance with the registered resource listener
     *
     * @param  object $plugin
     * @return void
     */
    public function injectResourceListener($plugin)
    {
        if (!is_object($plugin)) {
            return;
        }
		
		
        if (!method_exists($plugin, 'setResource')) {
            return;
        }

        $resource = $this->getResourceListener();
        if (!$resource instanceof AbstractResourceListener) {
            return;
        }

        $plugin->setResource($resource);
    }

    /**
     * Validate the plugin
     *
     * Any plugin is considered valid in this context.
     *
     * @param  mixed                            $plugin
     * @return void
     * @throws Exception\InvalidPluginException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Plugin\PluginInterface) {
            // we're okay
            return;
        }

        throw new Exception\InvalidPluginException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Plugin\PluginInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
