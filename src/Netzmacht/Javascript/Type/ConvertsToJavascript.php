<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Type;

use Netzmacht\Javascript\Encoder;

/**
 * Interface ConvertsToJavascript describes an object that can be converted to a javascript representation.
 *
 * @package Netzmacht\Javascript\Type
 */
interface ConvertsToJavascript
{
    /**
     * Encode the javascript representation of the object.
     *
     * @param Encoder $encoder The javascript encoder.
     * @param bool    $finish  If true the statement should be finished with an semicolon.
     *
     * @return string
     */
    public function encode(Encoder $encoder, $finish = true);
}
