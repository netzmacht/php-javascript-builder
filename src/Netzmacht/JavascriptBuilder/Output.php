<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder;

/**
 * Class Output collects all output during compiling.
 *
 * @package Netzmacht\Javascript
 */
class Output
{
    /**
     * Generated lines.
     *
     * @var string
     */
    private $buffer = '';

    /**
     * Separator.
     *
     * @var string
     */
    private $separator = "\n";

    /**
     * Add a line.
     *
     * @param string $line The built line.
     *
     * @return $this
     */
    public function append($line)
    {
        $this->buffer .= $this->separator . $line;

        return $this;
    }

    /**
     * Get lines.
     *
     * @return string
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Clear the buffer.
     *
     * @return $this
     */
    public function clear()
    {
        $this->buffer = '';

        return $this;
    }

    /**
     * Convert to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->buffer;
    }
}
