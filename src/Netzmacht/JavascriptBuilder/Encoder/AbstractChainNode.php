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
}
