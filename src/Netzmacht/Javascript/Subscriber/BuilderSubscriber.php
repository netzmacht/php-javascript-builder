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

use Netzmacht\Javascript\Builder;
use Netzmacht\Javascript\Event\BuildValueEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Type\Call\AbstractCall;
use Netzmacht\Javascript\Type\ConvertsToJavascript;
use Netzmacht\Javascript\Type\Value\ConvertsToArray;
use Netzmacht\Javascript\Type\Value\ConvertsToJson;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class BuilderSubscriber subscribes the builder events.
 *
 * @package Netzmacht\Javascript\Subscriber
 */
class BuilderSubscriber implements EventSubscriberInterface
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
            BuildValueEvent::NAME   => array('handleBuildValue', 100),
            GetReferenceEvent::NAME => array('handleGetReference', 100)
        );
    }

    /**
     * Handle the build value event.
     *
     * @param BuildValueEvent $event The build value event.
     *
     * @return void
     */
    public function handleBuildValue(BuildValueEvent $event)
    {
        if ($event->isSuccessful()) {
            return;
        }

        $builder = $event->getBuilder();
        $value   = $event->getValue();

        if ($value instanceof ConvertsToJavascript) {
            $event->setResult($value->build($builder));
        }

        if ($event->getReferenced() === $builder::VALUE_REFERENCE_REQUIRED) {
            if ($this->canBeReferenced($value)) {
                $event->setResult($builder->buildReference($value));

                return;
            } elseif ($value instanceof AbstractCall) {
                $event->setResult($value->build($builder, false));

                return;
            }
        }

        if (in_array(gettype($value), static::$native)) {
            $event->setResult($this->buildNative($value));
        } elseif ($value instanceof ConvertsToJson) {
            $event->setResult($value->toJson());
        } elseif ($this->isArray($value)) {
            $event->setResult($this->buildArray($value, $builder, $event->getJsonFlags()));
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
     * Build a native value.
     *
     * @param mixed $value The native value.
     * @param int   $flags Json flags.
     *
     * @return string
     */
    public function buildNative($value, $flags = null)
    {
        return json_encode($value, $flags);
    }

    /**
     * Build an array.
     *
     * @param array   $data    The data being built.
     * @param Builder $builder The builder.
     *
     * @return string
     */
    public function buildArray($data, Builder $builder)
    {
        $data = array_map(
            function ($item) use ($builder) {
                return $builder->buildValue($item, $builder::VALUE_REFERENCE_REQUIRED);
            },
            $data
        );

        $buffer = '';

        foreach ($data as $key => $value) {
            if (strlen($buffer)) {
                $buffer .= ', ';
            }

            $buffer .= ctype_alnum($key) ? $key : ('"' . $key . '"');
            $buffer .= ': ' . $value;
        }

        return '{ ' . $buffer . ' }';
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
            && (!$value instanceof AbstractCall);
    }
}
