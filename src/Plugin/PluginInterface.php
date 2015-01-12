<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\Rest\Plugin;
use ZF\Rest\AbstractResourceListener;

interface PluginInterface
{
    /**
     * Set the current resource listener instance
     *
     * @param  AbstractResourceListener $resource
     * @return void
     */
    public function setResource(AbstractResourceListener $resource);

    /**
     * Get the current resource listener instance
     *
     * @return null|AbstractResourceListener
     */
    public function getResource();
}
