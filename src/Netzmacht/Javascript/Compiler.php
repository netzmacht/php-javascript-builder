<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript;

use Netzmacht\Javascript\Event\CompileEvent;
use Netzmacht\Javascript\Subscriber\CompileSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class Compiler is an event driven compiler.
 *
 * @package Netzmacht\Javascript
 */
class Compiler
{
    /**
     * The javascript builder.
     *
     * @var Builder
     */
    private $builder;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Construct.
     *
     * @param Builder $builder
     * @param EventDispatcher $dispatcher
     */
    function __construct(Builder $builder, EventDispatcher $dispatcher)
    {
        $this->builder    = $builder;
        $this->dispatcher = $dispatcher;
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
     * Get the event dispatcher.
     *
     * @return EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Compile an object.
     *
     * @param object $object The object being compiled.
     * @param Output $output Optional pass an output instance.
     *
     * @return string
     */
    public function compile($object, Output $output = null)
    {
        $output = $output ?: new Output();

        $subscriber = new CompileSubscriber($this->builder, $output);
        $this->dispatcher->addSubscriber($subscriber);

        $event = new CompileEvent($object, $this->builder, $output);
        $this->dispatcher->dispatch($event::NAME, $event);

        $this->dispatcher->removeSubscriber($subscriber);

        return (string) $output;
    }
}
