<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Event;


use Netzmacht\Javascript\Builder;
use Netzmacht\Javascript\Subscriber;
use Symfony\Component\EventDispatcher\Event;

class BuildValueEvent extends Event
{
    const NAME = 'javascript-builder.build-value';

    private $value;

    private $referenced;

    private $result;

    private $successful = false;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var null|int
     */
    private $flags;

    /**
     * @param Builder $builder
     * @param         $value
     * @param int     $referenced
     * @param         $flags
     */
    function __construct(Builder $builder, $value, $referenced = Builder::VALUE_DEFINE, $flags = null)
    {
        $this->value      = $value;
        $this->referenced = $referenced;
        $this->builder = $builder;
        $this->flags = $flags;
    }

    /**
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getReferenced()
    {
        return $this->referenced;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     *
     * @return $this
     */
    public function setResult($result)
    {
        $this->result     = $result;
        $this->successful = true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    public function getJsonFlags()
    {
        return $this->flags;
    }
}
