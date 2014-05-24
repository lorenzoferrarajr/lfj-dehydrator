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
    public function addPlugin($plugin, $type = null)
    {
        try {
            $pluginReflection = new \ReflectionClass($plugin);
            if ($pluginReflection->implementsInterface('Lfj\Dehydrator\Plugin\PluginInterface')) {
                $this->plugins->append(array('plugin' => $plugin, 'type' => $type));
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
        foreach ($this->plugins as $p) {
            $plugin = new $p['plugin'];
            $plugin->setContent($content);
            $plugin->setUrl($url);
            $this->runPlugin($plugin, $p['type']);
        }

        return $this;
    }

    /**
     * Checks if a plugin is enabled, runs the plugin and saves the result
     *
     *
     * @param PluginInterface $plugin
     */
    public function runPlugin(PluginInterface $plugin, $type)
    {
        if ($plugin->isEnabled()) {
            $plugin->run();

            $pluginKey = $plugin->getKey();
            $pluginResult = $plugin->getResult();

            if ('replaceable' == $type) {
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
