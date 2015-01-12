<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Type\Value;

/**
 * Interface ConvertsToJson describes an object which serialize to a json object.
 *
 * The \JsonSerializable is not used for it so that you can explicit define which object should be encoded as json.
 *
 * @package Netzmacht\Javascript\Type\Value
 */
interface ConvertsToJson extends \JsonSerializable
{
}
