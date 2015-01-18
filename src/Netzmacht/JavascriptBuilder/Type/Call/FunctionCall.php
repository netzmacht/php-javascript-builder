<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Type\Call;

use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Type\Arguments;

/**
 * Class FunctionCall is used for named function calls. They have to be defined somewhere else.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Call
 */
class FunctionCall extends Arguments
{
    /**
     * Function name.
     *
     * @var string
     */
    private $name;

    /**
     * Construct.
     *
     * @param string $name       Function name.
     * @param array  $arguments  Method arguments.
     * @param null   $definition Linked definition.
     */
    public function __construct($name, array $arguments = array(), $definition = null)
    {
        parent::__construct($arguments, $definition);

        $this->name = $name;
    }

    /**
     * Get method name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function encode(Encoder $encoder, $flags = null)
    {
        return sprintf(
            '%s(%s)%s',
            $this->getName(),
            $encoder->encodeArguments($this->getArguments(), $flags),
            $encoder->close($flags)
        );
    }
}