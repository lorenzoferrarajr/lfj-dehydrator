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
use Zend\Uri\UriInterface;

class Dehydrator
{
    protected $result;
    protected $plugins;

    public function __construct()
    {
        $this->plugins = array();
        $this->result  = array();
    }

    /**
     * Add a plugin. Plugins must be added using full
     *
     * @param string $plugin
     * @param string $type Use "replaceable" or anything else
     * @return boolean
     */
    public function addPlugin($plugin, $type = null)
    {
        try {
            $pluginReflection = new \ReflectionClass($plugin);
            if ($pluginReflection->implementsInterface('Lfj\Dehydrator\Plugin\PluginInterface')) {
                $this->plugins[] = array('plugin' => $plugin, 'type' => $type);
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
     * @return self
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
     * @param string $type Use "replaceable" or anything else
     */
    public function runPlugin(PluginInterface $plugin, $type)
    {
        if ($plugin->isEnabled()) {
            $plugin->run();

            $pluginKey = $plugin->getKey();
            $pluginResult = $plugin->getResult();

            if ('replaceable' == $type) {
                $this->result[$pluginKey] = $pluginResult;
            } else {
                if (!is_array($pluginResult)) $pluginResult = array($pluginResult);

                if (isset($this->result[$pluginKey])) {
                    $this->result[$pluginKey] = array_merge($this->result[$pluginKey], $pluginResult);
                } else {
                    $this->result[$pluginKey] = $pluginResult;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}
