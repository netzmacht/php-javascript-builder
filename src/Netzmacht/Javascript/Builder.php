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

use Netzmacht\Javascript\Event\BuildEvent;
use Netzmacht\Javascript\Subscriber\BuildSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class Builder is an event driven javascript builder.
 *
 * @package Netzmacht\Javascript
 */
class Builder
{
    /**
     * The javascript encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Construct.
     *
     * @param Encoder         $encoder    The encoder.
     * @param EventDispatcher $dispatcher The event dispatcher.
     */
    public function __construct(Encoder $encoder, EventDispatcher $dispatcher)
    {
        $this->encoder    = $encoder;
        $this->dispatcher = $dispatcher;
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
     * Get the event dispatcher.
     *
     * @return EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Build an object.
     *
     * @param object $object The object being built.
     * @param Output $output Optional pass an output instance.
     *
     * @return string
     */
    public function build($object, Output $output = null)
    {
        $output = $output ?: new Output();

        $subscriber = new BuildSubscriber($this->encoder, $output);
        $this->dispatcher->addSubscriber($subscriber);

        $event = new BuildEvent($object, $this->encoder, $output);
        $this->dispatcher->dispatch($event::NAME, $event);

        $this->dispatcher->removeSubscriber($subscriber);

        return (string) $output;
    }
}
