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

use Netzmacht\Javascript\Event\EncodeValueEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Exception\EncodeValueFailed;
use Netzmacht\Javascript\Exception\GetReferenceFailed;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Encoder provides methods to encode javascript for several input types.
 *
 * @package Netzmacht\Javascript
 */
class Encoder
{
    const DEFINE              = 0;
    const BUILD               = 1;
    const REFERENCE_PREFERRED = 2;
    const REFERENCE_REQUIRED  = 4;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Json encoding flags.
     *
     * @var int
     */
    private $jsonEncodeFlags;

    /**
     * Construct.
     *
     * @param EventDispatcherInterface $dispatcher      The event dispatcher.
     * @param int|null                 $jsonEncodeFlags Json encoding flag.
     */
    public function __construct(EventDispatcherInterface $dispatcher, $jsonEncodeFlags = null)
    {
        $this->dispatcher      = $dispatcher;
        $this->jsonEncodeFlags = $jsonEncodeFlags;
    }

    /**
     * Encode javascript value for a given input.
     *
     * @param mixed $value      The given value.
     * @param int   $referenced Value reference state.
     *
     * @return string
     *
     * @throws EncodeValueFailed If no value could be built.
     */
    public function encodeValue($value, $referenced = self::BUILD)
    {
        $event = new EncodeValueEvent($this, $value, $referenced, $this->jsonEncodeFlags);
        $this->dispatcher->dispatch($event::NAME, $event);

        if ($event->isSuccessful()) {
            return implode("\n", $event->getLines());
        }

        throw new EncodeValueFailed($value, $referenced);
    }

    /**
     * Encode a list of arguments.
     *
     * @param array $arguments List of arguments.
     *
     * @return string
     */
    public function encodeArguments(array $arguments)
    {
        $encoded = array();

        foreach ($arguments as $argument) {
            $encoded[] = $this->encodeValue($argument, static::REFERENCE_REQUIRED);
        }

        return implode(', ', $encoded);
    }

    /**
     * Encode reference of given variable/object.
     *
     * @param object $object Encode reference for an object.
     *
     * @return string
     *
     * @throws GetReferenceFailed If no reference was created.
     */
    public function encodeReference($object)
    {
        $event = new GetReferenceEvent($object);
        $this->dispatcher->dispatch($event::NAME, $event);

        if ($event->getReference()) {
            return $event->getReference();
        }

        throw new GetReferenceFailed($object);
    }
}
