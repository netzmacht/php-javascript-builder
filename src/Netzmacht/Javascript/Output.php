<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript;

/**
 * Class Output
 *
 * @package Netzmacht\Javascript
 */
class Output
{
    /**
     * Generated lines.
     *
     * @var string[]
     */
    private $lines = array();

    /**
     * Add a line.
     *
     * @param string $line Compiled line.
     *
     * @return $this
     */
    public function addLine($line)
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Add lines.
     *
     * @param array|string $lines Lines.
     *
     * @return $this
     */
    public function addLines($lines)
    {
        if (is_string($lines)) {
            $lines = explode("\n", $lines);
        }

        foreach ($lines as $line) {
            $this->addLine($line);
        }

        return $this;
    }

    /**
     * Get lines.
     *
     * @return \string[]
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Convert to string.
     *
     * @return string
     */
    public function __toString()
    {
        return implode("\n", $this->getLines());
    }
}
