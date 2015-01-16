<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Encoder;

use Netzmacht\JavascriptBuilder\Encoder;

/**
 * Class DelegateEncoder is designed to delegate every method call to an child encoder.
 *
 * This class is made as base class for composite encoders so that they do not have to implement every delegate itself.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
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
    public function __construct(ChainNode $encoder)
    {
        $encoder->setEncoder($this);

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
        if ($this->encoder != $this) {
            $this->encoder->setEncoder($encoder);
        }

        $this->root = $encoder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFlags($flags)
    {
        $this->encoder->setFlags($flags);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlags()
    {
        return $this->encoder->getFlags();
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
