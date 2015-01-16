<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Encoder;

use Netzmacht\Javascript\Encoder;

/**
 * Class DelegateEncoder is designed to delegate every method call to an child encoder.
 *
 * This class is made as base class for composite encoders so that they do not have to implement every delegate itself.
 *
 * @package Netzmacht\Javascript\Encoder
 */
class DelegateEncoder implements ChainNode
{
    /**
     * The encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * The root encoder.
     *
     * @var Encoder
     */
    private $root;

    /**
     * Construct.
     *
     * @param ChainNode $encoder The encoder to which every call is delegated.
     */
    function __construct(ChainNode $encoder)
    {
        $this->encoder = $encoder;
        $this->root    = $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoder()
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncoder(Encoder $encoder)
    {
        $this->root = $encoder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        return $this->encoder->getOutput();
    }

    /**
     * {@inheritdoc}
     */
    public function encodeValue($value, $flags = null)
    {
        return $this->encoder->encodeValue($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArguments(array $arguments, $flags = null)
    {
        return $this->encoder->encodeArguments($arguments, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArray(array $data, $flags = null)
    {
        return $this->encoder->encodeArray($data, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeReference($value)
    {
        return $this->encoder->encodeReference($value);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeScalar($value, $flags = null)
    {
        return $this->encoder->encodeScalar($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        return $this->encoder->encodeObject($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function close($flags)
    {
        return $this->encoder->close($flags);
    }
}
