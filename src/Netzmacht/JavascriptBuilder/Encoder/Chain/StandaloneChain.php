<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Encoder\Chain;

use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Encoder\Chain;

/**
 * The standalone chain can be used if an Encoder should be used outside of a chain context.
 *
 * Be careful for the implementation of the Encoder. The usage of the standalone chain could easily run into an
 * endless loop.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder\Chain
 */
class StandaloneChain implements Chain
{
    /**
     * The encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * Construct.
     *
     * @param Encoder $encoder The encoder.
     */
    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * The the outside encoder.
     *
     * @return Encoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function next($method, array $arguments = array())
    {
        return $this->encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext($method)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function first($method)
    {
        return $this->encoder;
    }
}
