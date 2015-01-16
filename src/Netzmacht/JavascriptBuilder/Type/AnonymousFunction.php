<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Type;

use Netzmacht\JavascriptBuilder\Encoder;

/**
 * Class AnonymousCall is used for anonymous function calls.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Call
 */
class AnonymousFunction extends Arguments
{
    /**
     * Function lines.
     *
     * @var array
     */
    private $lines = array();

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
    public function encode(Encoder $encoder, $flags = Encoder::CLOSE_STATEMENT)
    {
        return sprintf(
            'function(%s) { %s } %s',
            parent::encode($encoder, $flags),
            implode("\n", $this->getLines()),
            $encoder->close($flags)
        );
    }
}
