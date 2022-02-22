<?php

/**
 * This file is part of the netzmacht/php-javascript-builder class
 *
 * @package    php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2022 netzmacht creative David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Type\Call;

use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Type\Arguments;
use Netzmacht\JavascriptBuilder\Type\ConvertsToJavascript;

/**
 * Class FunctionCall is used for named function calls. They have to be defined somewhere else.
 *
 * @package Netzmacht\JavascriptBuilder\Type\Call
 */
class FunctionCall implements ConvertsToJavascript
{
    /**
     * Function name.
     *
     * @var string
     */
    private $name;

    /**
     * Arguments.
     *
     * @var Arguments
     */
    private $arguments;

    /**
     * Construct.
     *
     * @param string $name      Function name.
     * @param array  $arguments Method arguments.
     */
    public function __construct($name, array $arguments = array())
    {
        $this->arguments = new Arguments($arguments);
        $this->name      = $name;
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
     * Get the arguments.
     *
     * @return Arguments
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
        return sprintf(
            '%s(%s)%s',
            $this->getName(),
            $this->arguments->encode($encoder, $flags),
            $encoder->close($flags)
        );
    }
}
