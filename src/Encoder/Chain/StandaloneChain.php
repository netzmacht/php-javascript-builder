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

namespace Netzmacht\JavascriptBuilder\Encoder\Chain;

use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Encoder\Chain;
use Netzmacht\JavascriptBuilder\Encoder\ChainNode;

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
    public function next(ChainNode $current, $method, array $arguments = array())
    {
        return call_user_func_array([$this->encoder, $method], $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext(ChainNode $current, $method)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function first($method, array $arguments = array())
    {
        return call_user_func_array([$this->encoder, $method], $arguments);
    }
}
