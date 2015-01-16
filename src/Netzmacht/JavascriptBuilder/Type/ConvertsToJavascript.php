<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Type;

use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Output;

/**
 * Interface ConvertsToJavascript describes an object that can be converted to a javascript representation.
 *
 * @package Netzmacht\JavascriptBuilder\Type
 */
interface ConvertsToJavascript
{
    /**
     * Encode the javascript representation of the object.
     *
     * @param Encoder  $encoder The javascript encoder.
     * @param int|null $flags   The encoding flags.
     *
     * @return string
     */
    public function encode(Encoder $encoder, $flags = null);
}
