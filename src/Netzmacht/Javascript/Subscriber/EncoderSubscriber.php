<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Subscriber;

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Event\EncodeValueEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Type\Call\AbstractCall;
use Netzmacht\Javascript\Type\ConvertsToJavascript;
use Netzmacht\Javascript\Type\Value\ConvertsToArray;
use Netzmacht\Javascript\Type\Value\ConvertsToJson;
use Netzmacht\Javascript\Type\Value\Reference;
use Netzmacht\LeafletPHP\Definition\UI\Marker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EncoderSubscriber subscribes the encoder events.
 *
 * @package Netzmacht\Javascript\Subscriber
 */
class EncoderSubscriber implements EventSubscriberInterface
{
    /**
     * List of native values.
     *
     * @var array
     */
    private static $native = array('string', 'integer', 'double', 'NULL', 'boolean');

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            EncodeValueEvent::NAME  => array('handleEncodeValue', -100),
            GetReferenceEvent::NAME => array('handleGetReference', -100)
        );
    }

    /**
     * Handle the encode value event.
     *
     * @param EncodeValueEvent $event The encode value event.
     *
     * @return void
     */
    public function handleEncodeValue(EncodeValueEvent $event)
    {
        if ($event->isSuccessful()) {
            return;
        }

        $encoder = $event->getEncoder();
        $value   = $event->getValue();

        if ($value instanceof ConvertsToJavascript) {
            $event->addLine($value->encode($encoder));
        }

        if ($event->getReferenced() === $encoder::VALUE_REFERENCE_REQUIRED) {
            if ($this->canBeReferenced($value)) {
                $event->addLine($encoder->encodeReference($value));

                return;
            } elseif ($value instanceof AbstractCall) {
                $event->addLine($value->encode($encoder, false));

                return;
            }
        }

        if (in_array(gettype($value), static::$native)) {
            $event->addLine($this->encodeNative($value, $event->getJsonFlags()));
        } elseif ($value instanceof \JsonSerializable) {
            $event->addLine($this->encodeNative($value, $event->getJsonFlags()));
        } elseif ($this->isArray($value)) {
            $event->addLine($this->encodeArray($value, $encoder, $event->getJsonFlags()));
        }
    }

    /**
     * Create reference.
     *
     * @param GetReferenceEvent $event The subscribed event.
     *
     * @return void
     */
    public function handleGetReference(GetReferenceEvent $event)
    {
        if ($event->getReference()) {
            return;
        }

        $object = $event->getObject();

        if (is_object($object) && !$object instanceof AbstractCall) {
            $event->setReference(spl_object_hash($object));
        }
    }

    /**
     * Encode a native value.
     *
     * @param mixed $value The native value.
     * @param int   $flags Json flags.
     *
     * @return string
     */
    public function encodeNative($value, $flags = null)
    {
        return json_encode($value, $flags);
    }

    /**
     * Encode an array.
     *
     * @param array   $data    The data being built.
     * @param Encoder $encoder The encoder.
     *
     * @return string
     */
    public function encodeArray($data, Encoder $encoder)
    {
        $buffer   = '';
        $numeric  = !empty($data);

        foreach ($data as $key => $value) {
            if (strlen($buffer)) {
                $buffer .= ', ';
            }

            if (is_numeric($key)) {
                $buffer .= $encoder->encodeValue($value, Encoder::VALUE_REFERENCE_REQUIRED);
            } else {
                $buffer .= ctype_alnum($key) ? $key : ('"' . $key . '"');
                $buffer .= ': ' . $encoder->encodeValue($value, Encoder::VALUE_REFERENCE_REQUIRED);

                $numeric = false;
            }
        }

        if ($numeric) {
            return '[' . $buffer . ']';
        } else {
            return '{' . $buffer . '}';
        }
    }

    /**
     * Check if value is an array. Convert it if possible.
     *
     * @param mixed $value The value.
     *
     * @return bool
     */
    private function isArray(&$value)
    {
        if ($value instanceof ConvertsToArray) {
            $value = $value->toArray();
        }

        return is_array($value) || $value instanceof \ArrayObject;
    }

    /**
     * Check if value can be referenced.
     *
     * @param mixed $value The value.
     *
     * @return bool
     */
    private function canBeReferenced($value)
    {
        return is_object($value)
            && (!$value instanceof ConvertsToArray)
            && (!$value instanceof ConvertsToJson)
            && (!$value instanceof AbstractCall)
            && (!$value instanceof Reference);
    }
}
