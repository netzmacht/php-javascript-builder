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
use Netzmacht\Javascript\Event\CompileEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Exception\EncodeValueFailed;
use Netzmacht\Javascript\Output;
use Netzmacht\Javascript\Type\Call\AnonymousFunction;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CompileSubscriber subscribes event which occurs during the compile process.
 *
 * @package Netzmacht\Javascript\Subscriber
 */
class CompileSubscriber implements EventSubscriberInterface
{
    /**
     * The encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * The output.
     *
     * @var Output
     */
    private $output;

    /**
     * Stack of instances.
     *
     * @var array
     */
    private $stack = array();

    /**
     * Construct.
     *
     * @param Encoder $encoder The encoder.
     * @param Output  $output  The output.
     */
    public function __construct(Encoder $encoder, Output $output)
    {
        $this->encoder = $encoder;
        $this->output  = $output;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            EncodeValueEvent::NAME   => array('handleClosures', -100),
            GetReferenceEvent::NAME => array('handleGetReference'),
            CompileEvent::NAME      => array('handleCompile', 100),
        );
    }

    /**
     * Instantiate referenced objects.
     *
     * @param EncodeValueEvent $event The Subscribed event.
     *
     * @return void
     *
     * @throws EncodeValueFailed If value could not being encoded.
     */
    public function handleClosures(EncodeValueEvent $event)
    {
        $object = $event->getValue();

        if ($object instanceof AnonymousFunction) {
            $this->compile($object, $this->encoder, $this->output);
        }
    }

    /**
     * Handle compile event.
     *
     * @param CompileEvent $event The subscribed event.
     *
     * @return void
     *
     * @throws EncodeValueFailed If value could not being encoded.
     */
    public function handleCompile(CompileEvent $event)
    {
        if ($event->getOutput()->getLines()) {
            return;
        }

        $this->compile($event->getObject(), $event->getEncoder(), $event->getOutput());
    }

    /**
     * Handle get reference event.
     *
     * @param GetReferenceEvent $event The subscribed event.
     *
     * @return void
     */
    public function handleGetReference(GetReferenceEvent $event)
    {
        $object = $event->getObject();
        $this->compile($object, $this->encoder, $this->output);
    }

    /**
     * Compile an object.
     *
     * @param mixed   $object  The object being compiled.
     * @param Encoder $encoder The encoder.
     * @param Output  $output  The output.
     *
     * @return void
     *
     * @throws EncodeValueFailed If encoding a value failed.
     */
    public function compile($object, Encoder $encoder, Output $output)
    {
        $hash = spl_object_hash($object);

        if (!isset($this->stack[$hash])) {
            $this->stack[$hash] = $object;
            $compiled           = $encoder->encodeValue($object);

            $output->addLines($compiled);
        }
    }
}
