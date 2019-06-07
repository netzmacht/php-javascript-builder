<?php

/**
 * PHP Javascript Builder
 *
 * @package    netzmacht/php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/php-javascript-builder/blob/master/LICENSE
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Symfony\Event;

use Netzmacht\JavascriptBuilder\Encoder;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EncodeValueEvent is emitted when a value is being encoded.
 *
 * @package Netzmacht\JavascriptBuilder\Event
 */
class EncodeValueEvent extends Event
{
    const NAME = 'javascript-builder.encode-value';

    /**
     * The value.
     *
     * @var mixed
     */
    private $value;

    /**
     * The created result.
     *
     * @var array
     */
    private $lines = array();

    /**
     * Successful state.
     *
     * @var bool
     */
    private $successful = false;

    /**
     * The encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * Json_encode flags.
     *
     * @var null|int
     */
    private $flags;

    /**
     * Construct.
     *
     * @param Encoder $encoder        The encoder.
     * @param mixed   $value          The value.
     * @param int     $jsonEncodeFlag The json_encode flags.
     */
    public function __construct(Encoder $encoder, $value, $jsonEncodeFlag = null)
    {
        $this->value   = $value;
        $this->encoder = $encoder;
        $this->flags   = $jsonEncodeFlag;
    }

    /**
     * Get the encoder.
     *
     * @return Encoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the result.
     *
     * @return array
     */
    public function getResult()
    {
        return implode("\n", $this->lines);
    }

    /**
     * Add a result line.
     *
     * @param mixed $result     The encoded javascript result.
     * @param bool  $successful Mark result as successful.
     *
     * @return $this
     */
    public function addLine($result, $successful = true)
    {
        $this->lines[] = $result;

        if ($successful) {
            $this->successful = true;
        }

        return $this;
    }

    /**
     * Check if encoding was successful.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Mark encoded value as successful no matter if any content was set.
     *
     * @return $this
     */
    public function setSuccessful()
    {
        $this->successful = true;

        return $this;
    }

    /**
     * Get the json flags.
     *
     * @return int|null
     */
    public function getJsonFlags()
    {
        return $this->flags;
    }
}
