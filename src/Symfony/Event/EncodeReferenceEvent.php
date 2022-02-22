<?php

/**
 * This file is part of the netzmacht/php-javascript-builder class
 *
 * @package    php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2022 netzmacht creative David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Symfony\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class GetReferenceEvent is emitted when a string reference of an variable is requested.
 *
 * @package Netzmacht\JavascriptBuilder\Event
 */
class EncodeReferenceEvent extends Event
{
    const NAME = 'javascript-builder.encode-reference';

    /**
     * The object.
     *
     * @var mixed
     */
    private $object;

    /**
     * The reference.
     *
     * @var string
     */
    private $reference;

    /**
     * Construct.
     *
     * @param object $object The object which should be referenced.
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Get the reference object.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Get the reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the object reference name.
     *
     * @param string $reference The reference name.
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }
}
