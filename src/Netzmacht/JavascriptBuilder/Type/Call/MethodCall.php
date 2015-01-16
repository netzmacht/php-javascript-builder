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

/**
 * Class MethodCall represents a javascript method call.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Call
 */
class MethodCall extends FunctionCall
{
    /**
     * The object of the method call.
     *
     * @var object
     */
    private $object;

    /**
     * Construct.
     *
     * @param string $object    The object of the method.
     * @param string $name      Function name.
     * @param array  $arguments Method arguments.
     */
    public function __construct($object, $name, array $arguments = array())
    {
        parent::__construct($name, $arguments);

        $this->object = $object;
    }

    /**
     * Get the object.
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * {@inheritdoc}
     */
    public function encode(Encoder $encoder, $flags = null)
    {
        return sprintf(
            '%s.%s',
            $encoder->encodeReference($this->object),
            parent::encode($encoder, $flags)
        );
    }
}
