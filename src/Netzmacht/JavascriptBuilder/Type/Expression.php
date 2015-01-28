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

use Netzmacht\JavascriptBuilder\Encoder;

/**
 * Class Expression defines a Javascript statement.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Value
 */
class Expression implements ConvertsToJavascript
{
    /**
     * The javascript expression.
     *
     * @var string
     */
    private $expression;

    /**
     * Construct.
     *
     * @param string $expression The javascript expression.
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Convert to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->expression;
    }

    /**
     * {@inheritdoc}
     */
    public function encode(Encoder $encoder, $flags = null)
    {
        return $this->expression . $encoder->close($flags);
    }
}
