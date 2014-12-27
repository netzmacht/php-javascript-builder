<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Event;

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Subscriber;
use Netzmacht\Javascript\Output;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CompileEvent is emitted when an object is being encoded.
 *
 * @package Netzmacht\Javascript\Event
 */
class CompileEvent extends Event
{
    const NAME = 'javascript-builder.compile';

    /**
     * The object being encoded.
     *
     * @var object
     */
    private $object;

    /**
     * The javascript encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * The compile output.
     *
     * @var Output
     */
    private $output;

    /**
     * Success state.
     *
     * @var bool
     */
    private $successful = false;

    /**
     * Construct.
     *
     * @param object  $object  The object being encoded.
     * @param Encoder $encoder The javascript encoder.
     * @param Output  $output  The generated output.
     */
    public function __construct($object, Encoder $encoder, Output $output)
    {
        $this->object  = $object;
        $this->encoder = $encoder;
        $this->output  = $output;
    }

    /**
     * Get the object which is compiled.
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Get the encoder.
     *
     * @return Encoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Get the output.
     *
     * @return Output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Mark compiling as successful.
     *
     * @return $this
     */
    public function setSuccessful()
    {
        $this->successful = true;

        return $this;
    }

    /**
     * Get success state.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }
}
