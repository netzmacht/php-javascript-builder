<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Type\Call;

use Netzmacht\Javascript\Type\Value\ConvertsToJavascript;

/**
 * Class AbstractCall is the base class for call representations.
 *
 * @package Netzmacht\Javascript\Type\Call
 */
abstract class AbstractCall implements ConvertsToJavascript
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
}
