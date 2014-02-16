<?php
/**
 * Lfj\Dehydrator
 *
 * @link      http://github.com/lorenzoferrarajr/dehydrator
 * @copyright 2014 Lorenzo Ferrara Junior
 * @license   ../LICENSE.TXT BSD-3-Clause
 */

namespace Lfj\Dehydrator\Content;

interface ContentInterface
{
    /**
     * @param string $string
     */
    public function __construct($string);

    /**
     * @return string|null
     */
    public function toString();
}
