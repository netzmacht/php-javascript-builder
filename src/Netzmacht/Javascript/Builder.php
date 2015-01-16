<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript;

use Netzmacht\Javascript\Encoder\JavascriptEncoder;
use Netzmacht\Javascript\Encoder\ResultCacheEncoder;
use Netzmacht\Javascript\Util\Flags;

/**
 * Class Builder is the main entry point to
 *
 * @package Netzmacht\Javascript
 */
class Builder
{
    /**
     * A callable which expects the Output object as argument and creates the encoder.
     *
     * @var \callable
     */
    private $encoderFactory;

    /**
     * Construct.
     *
     * @param \callable $encoderFactory The encoder factory.
     */
    public function __construct($encoderFactory = null)
    {
        if ($encoderFactory) {
            $this->setEncoderFactory($encoderFactory);

        } else {
            /**
             * @param Output $output
             *
             * @return ResultCacheEncoder
             */
            $this->encoderFactory = function(Output $output) {
                $encoder = new JavascriptEncoder($output);
                $cache   = new ResultCacheEncoder($encoder);

                return $cache;
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
    public function encode($value, $flags = Encoder::CLOSE_STATEMENT, Output $output = null)
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
