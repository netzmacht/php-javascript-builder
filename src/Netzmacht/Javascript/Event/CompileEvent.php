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

use Netzmacht\Javascript\Builder;
use Netzmacht\Javascript\Subscriber;
use Netzmacht\Javascript\Output;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CompileEvent is emitted when an object is being build.
 *
 * @package Netzmacht\Javascript\Event
 */
class CompileEvent extends Event
{
    const NAME = 'javascript-builder.compile';

    /**
     * The object being build.
     * @var object
     */
    private $object;

    /**
     * The javascript builder.
     *
     * @var Builder
     */
    private $builder;

    /**
     * The compile output.
     *
     * @var Output
     */
    private $output;

    /**
     * Construct.
     *
     * @param object  $object  The object being build.
     * @param Builder $builder The javascript builder.
     * @param Output  $output  The generated output.
     */
    function __construct($object, Builder $builder, Output $output)
    {
        $this->object  = $object;
        $this->builder = $builder;
        $this->output  = $output;
    }

    /**
     * Get the object build compiled.
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Get the builder.
     *
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
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
}
