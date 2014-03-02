<?php
/**
 * Lfj\Dehydrator
 *
 * @link      http://github.com/lorenzoferrarajr/dehydrator
 * @copyright 2014 Lorenzo Ferrara Junior
 * @license   ../LICENSE.TXT BSD-3-Clause
 */

namespace Lfj\Dehydrator;

use Lfj\Dehydrator\Content\ContentInterface;
use Lfj\Dehydrator\Plugin\PluginInterface;
use Lfj\Dehydrator\Plugin\ReplaceablePluginInterface;
use Zend\Uri\UriInterface;

class Dehydrator
{
    protected $result;
    protected $plugins;

    public function __construct()
    {
        $this->plugins = new \ArrayIterator();
        $this->result  = new \ArrayIterator();
    }

    /**
     * Add a plugin. Plugins must be added using full
     *
     * @param string $plugin
     * @return boolean
     */
    public function addPlugin($plugin)
    {
        $result = false;

        try {
            $pluginReflection = new \ReflectionClass($plugin);
            if ($pluginReflection->implementsInterface('Lfj\Dehydrator\Plugin\PluginInterface')) {
                $this->plugins->append($plugin);
                $result = true;
            }
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Read the document, run all plugins and return the extracted data
     *
     * @param UriInterface $url
     * @param ContentInterface $content
     * @return \ArrayIterator
     */
    public function dehydrate(UriInterface $url, ContentInterface $content)
    {
        foreach ($this->plugins as $plugin) {
            $plugin = new $plugin($url, $content);
            $this->runPlugin($plugin);
        }

        return $this;
    }

    /**
     * Checks if a plugin is enabled, runs the plugin and saves the result
     *
     *
     * @param PluginInterface $plugin
     */
    public function runPlugin(PluginInterface $plugin)
    {
        if ($plugin->isEnabled()) {
            $plugin->run();

            $pluginKey = $plugin->getKey();
            $pluginResult = $plugin->getResult();

            if ($plugin instanceof ReplaceablePluginInterface) {
                $this->getResult()->offsetSet($pluginKey, $pluginResult);
            } else {
                if (!is_array($pluginResult)) $pluginResult = array($pluginResult);

                if ($this->getResult()->offsetExists($pluginKey)) {
                    $this->getResult()->offsetSet($pluginKey, array_merge($this->getResult()->offsetGet($pluginKey), $pluginResult));
                } else {
                    $this->getResult()->offsetSet($pluginKey, $pluginResult);
                }
            }
        }
    }

    /**
     * @return \ArrayIterator
     */
    public function getResult()
    {
        return $this->result;
    }
}
