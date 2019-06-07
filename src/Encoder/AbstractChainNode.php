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

namespace Netzmacht\JavascriptBuilder\Encoder;

/**
 * The base implementation for chain node encoder subscribers.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
 */
abstract class AbstractChainNode implements ChainNode
{
    /**
     * The encoder chain.
     *
     * @var Chain
     */
    protected $chain;

    /**
     * {@inheritdoc}
     */
    public function setChain(Chain $chain)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function setFlags($flags)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function getFlags()
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeValue($value, $flags = null)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArguments(array $arguments, $flags = null)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArray(array $data, $flags = null)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeReference($value)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeScalar($value, $flags = null)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function close($flags)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectStack($value)
    {
        $this->throwNotSupported(__FUNCTION__);
    }

    /**
     * Throw a not implemented exception.
     *
     * @param string $method The method name.
     *
     * @return void
     *
     * @throws \BadMethodCallException For an invalid method call.
     */
    private function throwNotSupported($method)
    {
        throw new \BadMethodCallException(sprintf('Method %s not supported', $method));
    }
}
