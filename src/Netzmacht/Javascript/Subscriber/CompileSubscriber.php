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
use Netzmacht\Javascript\Event\CompileEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Exception\BuildValueFailed;
use Netzmacht\Javascript\Output;
use Netzmacht\LeafletPHP\JavaScript\Closure;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CompileSubscriber subscribes event which occurs during the compile process.
 *
 * @package Netzmacht\Javascript\Builder
 */
class CompileSubscriber implements EventSubscriberInterface
{
    /**
     * The builder.
     *
     * @var Builder
     */
    private $builder;

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
     * @param Builder $builder The builder.
     * @param Output  $output  The output.
     */
    public function __construct(Builder $builder, Output $output)
    {
        $this->builder = $builder;
        $this->output  = $output;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BuildValueEvent::NAME   => array('handleClosures', -100),
            GetReferenceEvent::NAME => array('handleGetReference'),
            CompileEvent::NAME      => array('handleCompile', 100),
        );
    }

    /**
     * Instantiate referenced objects.
     *
     * @param BuildValueEvent $event The Subscribed event.
     *
     * @return void
     *
     * @throws BuildValueFailed If value could not being build.
     */
    public function handleClosures(BuildValueEvent $event)
    {
        $object = $event->getValue();

        if ($object instanceof Closure) {
            $this->compile($object, $this->builder, $this->output);
        }
    }

    /**
     * Handle compile event.
     *
     * @param CompileEvent $event The subscribed event.
     *
     * @return void
     *
     * @throws BuildValueFailed If value could not being build.
     */
    public function handleCompile(CompileEvent $event)
    {
        if ($event->getOutput()->getLines()) {
            return;
        }

        $this->compile($event->getObject(), $event->getBuilder(), $event->getOutput());
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
        $this->compile($object, $this->builder, $this->output);
    }

    /**
     * Compile an object.
     *
     * @param mixed   $object  The object being compiled.
     * @param Builder $builder The builder.
     * @param Output  $output  The putput.
     *
     * @return void
     *
     * @throws BuildValueFailed If building a value failed.
     */
    public function compile($object, Builder $builder, Output $output)
    {
        $hash = spl_object_hash($object);

        if (!isset($this->stack[$hash])) {
            $this->stack[$hash] = $object;
            $compiled           = $builder->buildValue($object);

            $output->addLines($compiled);
        }
    }
}
