<?php

/**
 * PHP Javascript Builder
 *
 * @package    netzmacht/php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/php-javascript-builder/blob/master/LICENSE
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Type;

/**
 * Interface ReferencedByIdentifier describes elements which creates an identifier for being referenced.
 *
 * @package Netzmacht\JavascriptBuilder\Type
 */
interface ReferencedByIdentifier
{
    /**
     * Get the refence identifier as string.
     *
     * @return string
     */
    public function getReferenceIdentifier();
}
