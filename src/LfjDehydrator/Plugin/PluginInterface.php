<?php
/**
 * Lfj\Dehydrator
 *
 * @link      https://github.com/lorenzoferrarajr/lfj-dehydrator
 * @copyright 2014 Lorenzo Ferrara Junior
 * @license   ../LICENSE.TXT BSD-3-Clause
 */

namespace Lfj\Dehydrator\Plugin;

use Lfj\Dehydrator\Content\ContentInterface;
use Zend\Uri\UriInterface;

interface PluginInterface
{
    public function __construct();
    public function isEnabled();
    public function getKey();
    public function setResult($result);
    public function getResult();

    /**
     * @param UriInterface $url
     * @return self
     */
    public function setUrl(UriInterface $url);

    /**
     * @return UriInterface
     */
    public function getUrl();

    /**
     * @param ContentInterface $content
     * @return self
     */
    public function setContent(ContentInterface $content);

    /**
     * @return ContentInterface
     */
    public function getContent();

    public function run();
}