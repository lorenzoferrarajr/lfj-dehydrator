<?php
/**
 * Lfj\Dehydrator
 *
 * @link      http://github.com/lorenzoferrarajr/dehydrator
 * @copyright 2014 Lorenzo Ferrara Junior
 * @license   ../LICENSE.TXT BSD-3-Clause
 */

namespace Lfj\Dehydrator\Content;

/**
 * @property string $content
 */
class AbstractContent
{
    protected $string;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->string;
    }
}
