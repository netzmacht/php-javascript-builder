<?php

/**
 * This file is part of the netzmacht/php-javascript-builder class.
 *
 * @package    php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2022 netzmacht creative David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Encoder;

use Netzmacht\JavascriptBuilder\Encoder;

/**
 * Interface ChainNode describes encoders which can be in a chain.
 *
 * It is used so that the javascript encoder is able to delegate the sub calls to the visible encoder.
 */
interface ChainNode extends Encoder
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
