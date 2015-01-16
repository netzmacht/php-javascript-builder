<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Type;

/**
 * Interface ReferencedByIdentifier describes elements which creates an identifier for being referenced.
 *
 * @package Netzmacht\JavascriptBuilder\Type
 */
interface ReferencedByIdentifier
{
    public function getReferenceIdentifier();
}
