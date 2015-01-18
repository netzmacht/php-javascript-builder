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
 * Interface ChainNode describes encoders which can be in a chain.
 *
 * It is used so that the javascript encoder is able to delegate the sub calls to the visible encoder.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
 */
interface ChainNode
{
    /**
     * Get a list of the subscribed methods.
     *
     * @return array
     */
    public function getSubscribedMethods();

    /**
     * Set the corresponding Chain.
     *
     * @param Chain $chain The chain.
     *
     * @return $this
     */
    public function setChain(Chain $chain);
}
