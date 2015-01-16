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
 * Class AbstractCall is the base class for call representations.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Call
 */
class Arguments implements ConvertsToJavascript
{
    /**
     * Method arguments.
     *
     * @var array
     */
    private $arguments;

    /**
     * Construct.
     *
     * @param array $arguments Method arguments.
     */
    public function __construct(array $arguments = array())
    {
        $this->arguments = $arguments;
    }

    /**
     * Get method arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function encode(Encoder $encoder, $flags = null)
    {
        return $encoder->encodeArguments($this->getArguments(), $flags);
    }
}
