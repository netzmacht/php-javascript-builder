<?php

/**
 * This file is part of the netzmacht/php-javascript-builder class
 *
 * @package    php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2022 netzmacht creative David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Type;

/**
 * Interface ReferencedByIdentifier describes elements which creates an identifier for being referenced.
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
