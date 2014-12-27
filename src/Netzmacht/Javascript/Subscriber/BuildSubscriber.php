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
use Netzmacht\Javascript\Event\BuildEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Exception\EncodeValueFailed;
use Netzmacht\Javascript\Output;
use Netzmacht\Javascript\Type\Call\AnonymousFunction;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class BuildSubscriber subscribes event which occurs during the build process.
 *
 * @package Netzmacht\Javascript\Subscriber
 */
class BuildSubscriber implements EventSubscriberInterface
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
            EncodeValueEvent::NAME  => array('handleClosures'),
            GetReferenceEvent::NAME => array('handleGetReference'),
            BuildEvent::NAME        => array('handleBuild', 100),
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
            $this->build($object, $this->encoder, $this->output);
        }
    }

    /**
     * Handle build event.
     *
     * @param BuildEvent $event The subscribed event.
     *
     * @return void
     *
     * @throws EncodeValueFailed If value could not being encoded.
     */
    public function handleBuild(BuildEvent $event)
    {
        $this->build($event->getObject(), $event->getEncoder(), $event->getOutput());
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
        $this->build($object, $this->encoder, $this->output);
    }

    /**
     * Build an object.
     *
     * @param mixed   $object  The object being built.
     * @param Encoder $encoder The encoder.
     * @param Output  $output  The output.
     *
     * @return void
     *
     * @throws EncodeValueFailed If encoding a value failed.
     */
    public function build($object, Encoder $encoder, Output $output)
    {
        $hash = spl_object_hash($object);

        if (!isset($this->stack[$hash])) {
            $this->stack[$hash] = $object;

            $output->addLines($encoder->encodeValue($object));
        }
    }
}
