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

use Netzmacht\Javascript\Event\EncodeValueEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Type\Call\AbstractCall;
use Netzmacht\Javascript\Type\Value\ConvertsToJavascript;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EncoderSubscriber subscribes the encoder events.
 *
 * @package Netzmacht\Javascript\Subscriber
 */
class EncoderSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            EncodeValueEvent::NAME  => array('handleEncodeValue', -100),
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
        $output  = $encoder->getOutput();
        $value   = $event->getValue();

        if ($value instanceof ConvertsToJavascript) {
            $event->addLine($value->encode($encoder, $output));
        } elseif ($value instanceof \JsonSerializable) {
            $event->addLine($encoder->encodeScalar($value));
        }

        if ($value instanceof AbstractCall) {
            $event->addLine($value->encode($encoder, $output));
        }
    }
}
