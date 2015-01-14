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

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Output;

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
     * @param Output  $output  The generated output.
     * @param bool    $finish  Should be statement being finished.
     *
     * @return string
     */
    public function encode(Encoder $encoder, Output $output, $finish = true);
}
