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
     * Set separator.
     *
     * @param string $separator Separator.
     *
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Get separator.
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Add a line.
     *
     * @param string $line The built line.
     *
     * @return $this
     */
    public function append($line)
    {
        if ($this->buffer) {
            $line = $this->separator . $line;
        }

        $this->buffer .= $line;


        return $this;
    }

    /**
     * Add a line to the beginning.
     *
     * @param string $line The built line.
     *
     * @return $this
     */
    public function prepend($line)
    {
        if ($this->buffer) {
             $line .= $this->separator . $this->buffer;
        }

        $this->buffer = $line;


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
