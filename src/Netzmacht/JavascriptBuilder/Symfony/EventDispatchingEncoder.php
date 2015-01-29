<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Symfony;

use Netzmacht\JavascriptBuilder\Encoder\AbstractChainNode;
use Netzmacht\JavascriptBuilder\Symfony\Event\EncodeValueEvent;
use Netzmacht\JavascriptBuilder\Symfony\Event\EncodeReferenceEvent;
use Netzmacht\JavascriptBuilder\Symfony\Event\GetObjectStackEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class EventDispatchingEncoder dispatches the object encoding and reference generating to events.
 *
 * @package Netzmacht\JavascriptBuilder\Symfony
 */
class EventDispatchingEncoder extends AbstractChainNode
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Construct.
     *
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedMethods()
    {
        return array(
            'encodeObject',
            'encodeReference',
            'getObjectStack'
        );
    }

    /**
     * Encode an object by calling the encode value event.
     *
     * @param object|resource $value The given value.
     * @param null            $flags The encoder flags.
     *
     * @return array|string
     */
    public function encodeObject($value, $flags = null)
    {
        $event = new EncodeValueEvent($this->chain->getEncoder(), $value, $flags);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        if ($event->isSuccessful()) {
            return $event->getResult();
        }

        return $this->chain->next($this, __FUNCTION__, [$value, $flags]);
    }

    /**
     * Encode an reference by triggering the EncodeReferenceEvent.
     *
     * @param mixed $value The value which should be referenced.
     *
     * @return string
     */
    public function encodeReference($value)
    {
        $event = new EncodeReferenceEvent($value);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        if ($event->getReference()) {
            return $event->getReference();
        }

        return $this->chain->next($this, __FUNCTION__, [$value]);
    }

    /**
     * Get the stack of to encoded objects.
     *
     * @param object $value The object value.
     *
     * @return array
     */
    public function getObjectStack($value)
    {
        $event = new GetObjectStackEvent($value);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        if ($event->getStack()) {
            return $event->getStack();
        }

        if ($this->chain->hasNext($this, __FUNCTION__)) {
            return $this->chain->next($this, __FUNCTION__, [$value]);
        }

        return array();
    }
}
