<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Type\Value;

/**
 * ConvertsToArray is used to mark an object that it converts to an array.
 *
 * @package Netzmacht\Javascript\Type
 */
interface ConvertsToArray
{
    /**
     * Convert object to an array.
     *
     * @return array
     */
    public function toArray();
}
