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
     * Successful state.
     *
     * @var bool
     */
    private $successful = false;

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
        $this->stack      = $stack;
        $this->successful = true;

        return $this;
    }

    /**
     * Check if stack was set.
     *
     * @return bool
     */
    public function hasStack()
    {
        return $this->successful;
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
