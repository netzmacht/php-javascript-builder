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


use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Type\ConvertsToJavascript;

class Expression implements ConvertsToJavascript
{
    private $name;
    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Encode the javascript representation of the object.
     *
     * @param Encoder $encoder The javascript encoder.
     * @param bool    $finish  If true the statement should be finished with an semicolon.
     *
     * @return string
     */
    public function encode(Encoder $encoder, $finish = true)
    {
        return $this->name;
    }
}
