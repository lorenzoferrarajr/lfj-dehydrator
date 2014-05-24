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
     * Create a new Plugin object
     */
    public function __construct()
    {
        $this->url = null;
        $this->content = null;
        $this->result = array();
    }

    /**
     * @param ContentInterface $content
     * @return $this
     */
    public function setContent(ContentInterface $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return ContentInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param UriInterface $url
     * @return $this
     */
    public function setUrl(UriInterface $url)
    {
        $this->url = $url;
        return $this;
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
