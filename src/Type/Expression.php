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
