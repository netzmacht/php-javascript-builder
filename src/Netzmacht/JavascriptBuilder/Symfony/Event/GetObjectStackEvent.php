<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Symfony\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class GetObjectStackEvent is emitted then the object stack is requested to the event dispatcher encoder.
 *
 * @package Netzmacht\JavascriptBuilder\Symfony\Event
 */
class GetObjectStackEvent extends Event
{
    const NAME = 'javascript-builder.get-object-stack';

    /**
     * The current value.
     *
     * @var mixed
     */
    private $value;

    /**
     * The built stack.
     *
     * @var array
     */
    private $stack = array();

    /**
     * Construct.
     *
     * @param mixed $value The value being built.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the stack.
     *
     * @return array
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Set the stack.
     *
     * @param array $stack The stack.
     *
     * @return $this
     */
    public function setStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
