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

namespace Netzmacht\JavascriptBuilder;

use Netzmacht\JavascriptBuilder\Encoder\ChainEncoder;
use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Encoder\MultipleObjectsEncoder;

/**
 * Class Builder is the main entry point to encode an object.
 *
 * @package Netzmacht\Javascript
 */
class Builder
{
    /**
     * A callable which expects the Output object as argument and creates the encoder.
     *
     * @var callable
     */
    private $encoderFactory;

    /**
     * Construct.
     *
     * @param callable $encoderFactory The encoder factory.
     */
    public function __construct($encoderFactory = null)
    {
        if ($encoderFactory) {
            $this->setEncoderFactory($encoderFactory);
        } else {
            $this->encoderFactory = function (Output $output) {
                $encoder = new ChainEncoder();

                $encoder
                    ->register(new MultipleObjectsEncoder())
                    ->register(new JavascriptEncoder($output));

                return $encoder;
            };
        }
    }

    /**
     * Set the encoder factory.
     *
     * @param callable $encoderFactory The encoder factory.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException If an invalid factory is given.
     */
    public function setEncoderFactory($encoderFactory)
    {
        if (!is_callable($encoderFactory)) {
            throw new \InvalidArgumentException('Invalid encoder factory given. Must be an callable.');
        }

        $this->encoderFactory = $encoderFactory;

        return $this;
    }

    /**
     * Encode an value and return it.
     *
     * @param mixed    $value  The value being encoded.
     * @param int|null $flags  Optional encoder flags.
     * @param Output   $output The output being generated.
     *
     * @return string
     */
    public function encode($value, $flags = Flags::CLOSE_STATEMENT, Output $output = null)
    {
        $factory = $this->encoderFactory;
        $output  = $output ?: new Output();

        /** @var Encoder $encoder */
        $encoder = $factory($output);
        $flags   = Flags::add($flags, $encoder->getFlags());

        $output->append($encoder->encodeValue($value, $flags));

        return $output->getBuffer();
    }
}
