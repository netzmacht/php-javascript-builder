<?php

/**
 * PHP Javascript Builder
 *
 * @package    netzmacht/php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/php-javascript-builder/blob/master/LICENSE
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
