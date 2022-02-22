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
