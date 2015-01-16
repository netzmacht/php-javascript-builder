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

use Netzmacht\JavascriptBuilder\Encoder\ChainNode;
use Netzmacht\JavascriptBuilder\Encoder\DelegateEncoder;
use Netzmacht\JavascriptBuilder\Symfony\Event\EncodeValueEvent;
use Netzmacht\JavascriptBuilder\Symfony\Event\EncodeReferenceEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class EventDispatchingEncoder dispatches the object encoding and reference generating to events.
 *
 * @package Netzmacht\JavascriptBuilder\Symfony
 */
class EventDispatchingEncoder extends DelegateEncoder
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
     * @param ChainNode       $encoder         The child encoder.
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function __construct(ChainNode $encoder, EventDispatcher $eventDispatcher)
    {
        parent::__construct($encoder);

        $this->eventDispatcher = $eventDispatcher;
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
        $event = new EncodeValueEvent($this->getEncoder(), $value, $flags);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        if ($event->isSuccessful()) {
            return $event->getResult();
        }

        return parent::encodeObject($value, $flags);
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

        if ($event->getReference()) {
            return $event->getReference();
        }

        return parent::encodeReference($value);
    }
}
