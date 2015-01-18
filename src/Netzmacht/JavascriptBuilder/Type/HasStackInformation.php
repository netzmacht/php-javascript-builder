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
 * HasStackInformation is designed for objects with a large object graph so they can predefine the dependencies.
 *
 * Add this method to the object which is aware of the stack. It only works if the MultipleObjectsEncoder is used and
 * the Encoder::BUILD_STACK flag is set.
 *
 * @package Netzmacht\JavascriptBuilder\Type
 */
interface HasStackInformation
{
    /**
     * Get the stack of all to encode objects in an ordered row.
     *
     * @return array
     */
    public function getObjectStack();
}
