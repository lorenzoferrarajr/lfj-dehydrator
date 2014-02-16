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

/**
 * @property string $content
 * @property array $result;
 * @property UriInterface $url;
 */
abstract class AbstractPlugin
{
    protected $content;
    protected $result;
    protected $url;

    /**
     * Create a new Plugin object.
     *
     * @param UriInterface $url
     * @param null $content
     */
    public function __construct(UriInterface $url, ContentInterface $content)
    {
        $this->url = $url;
        $this->content = $content;
        $this->result = array();
    }

    /**
     * @return ContentInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return UriInterface
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }
}
