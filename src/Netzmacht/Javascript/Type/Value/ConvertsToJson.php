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
 * ConvertsToJson can be used to mark an object that is can be converted to a json string.
 *
 * @package Netzmacht\Javascript\Type
 */
interface ConvertsToJson
{
    /**
     * Get value as valid json string.
     *
     * @return string
     */
    public function toJson();
}
