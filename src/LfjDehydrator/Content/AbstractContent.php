<?php
/**
 * Lfj\Dehydrator
 *
 * @link      https://github.com/lorenzoferrarajr/lfj-dehydrator
 * @copyright 2014 Lorenzo Ferrara Junior
 * @license   ../LICENSE.TXT BSD-3-Clause
 */

namespace Lfj\Dehydrator\Content;

/**
 * @property string $string
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
