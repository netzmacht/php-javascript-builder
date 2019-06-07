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
 * Class AnonymousCall is used for anonymous function calls.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Call
 */
class AnonymousFunction implements ConvertsToJavascript
{
    /**
     * Function lines.
     *
     * @var array
     */
    private $lines = array();

    /**
     * Argument names.
     *
     * @var array
     */
    private $argumentNames = array();

    /**
     * Construct.
     *
     * @param array $argumentNames The argument names.
     */
    public function __construct($argumentNames = array())
    {
        $this->argumentNames = $argumentNames;
    }

    /**
     * Get argument names.
     *
     * @return array
     */
    public function getArgumentNames()
    {
        return $this->argumentNames;
    }

    /**
     * Add a new line.
     *
     * @param string $line New function line.
     *
     * @return $this
     */
    public function addLine($line)
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Add multiple lines.
     *
     * @param string|array $lines Set of lines. Can be an array or a list of lines separated by line breaks.
     *
     * @return $this
     */
    public function addLines($lines)
    {
        if (!is_array($lines)) {
            $lines = explode("\n", $lines);
        }

        foreach ($lines as $line) {
            $this->addLine($line);
        }

        return $this;
    }

    /**
     * Get all lines.
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * {@inheritdoc}
     */
    public function encode(Encoder $encoder, $flags = null)
    {
        return sprintf(
            'function(%s) { %s }%s',
            implode(', ', $this->argumentNames),
            implode("\n", $this->getLines()),
            $encoder->close($flags)
        );
    }
}
