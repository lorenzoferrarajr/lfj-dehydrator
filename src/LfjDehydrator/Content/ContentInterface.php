<?php
/**
 * Lfj\Dehydrator
 *
 * @link      https://github.com/lorenzoferrarajr/lfj-dehydrator
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
