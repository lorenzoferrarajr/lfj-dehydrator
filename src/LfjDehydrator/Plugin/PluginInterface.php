<?php
/**
 * Lfj\Dehydrator
 *
 * @link      http://github.com/lorenzoferrarajr/dehydrator
 * @copyright 2014 Lorenzo Ferrara Junior
 * @license   ../LICENSE.TXT BSD-3-Clause
 */

namespace Lfj\Dehydrator\Plugin;

use Lfj\Dehydrator\Content\ContentInterface;
use Zend\Uri\UriInterface;

interface PluginInterface
{
    public function __construct(UriInterface $url, ContentInterface $content);
    public function isEnabled();
    public function getKey();
    public function setResult($result);
    public function getResult();
    public function getUrl();
    public function getContent();
    public function run();
}