<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Event;

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Subscriber;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EncodeValueEvent is emitted when a value is being encoded.
 *
 * @package Netzmacht\Javascript\Event
 */
class EncodeValueEvent extends Event
{
    const NAME = 'javascript-builder.encode-value';

    /**
     * The value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Should an reference being created.
     *
     * @var int
     */
    private $referenced;

    /**
     * The created result.
     *
     * @var array
     */
    private $lines = array();

    /**
     * Successful state.
     *
     * @var bool
     */
    private $successful = false;

    /**
     * The encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * Json_encode flags.
     *
     * @var null|int
     */
    private $flags;

    /**
     * Construct.
     *
     * @param Encoder $encoder    The encoder.
     * @param mixed   $value      The value.
     * @param int     $referenced The reference flag.
     * @param int     $flags      The json_encode flags.
     */
    public function __construct(Encoder $encoder, $value, $referenced = Encoder::VALUE_DEFINE, $flags = null)
    {
        $this->value      = $value;
        $this->referenced = $referenced;
        $this->encoder    = $encoder;
        $this->flags      = $flags;
    }

    /**
     * Get the encoder.
     *
     * @return Encoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the referenced flag.
     *
     * @return int
     */
    public function getReferenced()
    {
        return $this->referenced;
    }

    /**
     * Get the result.
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Add a result line.
     *
     * @param mixed $result     The encoded javascript result.
     * @param bool  $successful Mark result as successful.
     *
     * @return $this
     */
    public function addLine($result, $successful = true)
    {
        $this->lines[] = $result;

        if ($successful) {
            $this->successful = true;
        }

        return $this;
    }

    /**
     * Add multiple lines.
     *
     * @param array $lines      The encoded javascript result as lines.
     * @param bool  $successful Mark result as successful.
     *
     * @return $this
     */
    public function addLines(array $lines, $successful = true)
    {
        foreach ($lines as $line) {
            $this->addLine($line, $successful);
        }

        return $this;
    }

    /**
     * Check if encoding was successful.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Get the json flags.
     *
     * @return int|null
     */
    public function getJsonFlags()
    {
        return $this->flags;
    }
}
