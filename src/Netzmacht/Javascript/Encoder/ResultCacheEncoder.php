<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Encoder;

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Util\Flags;

/**
 * Class ResultCacheEncoder provides a cache layer for the encoded result.
 *
 * @package Netzmacht\Javascript\Encoder
 */
class ResultCacheEncoder extends DelegateEncoder
{
    /**
     * Values cache.
     *
     * @var array
     */
    private $values = array();

    /**
     * References cache.
     *
     * @var array
     */
    private $references = array();

    /**
     * {@inheritdoc}
     */
    public function encodeValue($value, $flags = null)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->values)) {
            $this->values[$hash] = parent::encodeValue($value, $flags);
        }

        return $this->values[$hash];
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->values)) {
            $this->values[$hash] = parent::encodeObject($value, $flags);
        }

        return $this->values[$hash];
    }

    /**
     * {@inheritdoc}
     */
    public function encodeScalar($value, $flags = null)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->values)) {
            $this->values[$hash] = parent::encodeScalar($value, $flags);
        }

        return $this->values[$hash];
    }

    /**
     * {@inheritdoc}
     */
    public function encodeReference($value)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->references)) {
            $this->references[$hash] = parent::encodeReference($value);

            if ($this->references[$hash]) {
                $this->getOutput()->append(
                    $this->encodeValue($value, Flags::add(static::CLOSE_STATEMENT, $this->getFlags()))
                );
            }
        }

        return $this->references[$hash];
    }

    /**
     * Create an unique hash of a value.
     *
     * @param mixed $value The value.
     *
     * @return string
     */
    private function hash($value)
    {
        if (is_object($value)) {
            return spl_object_hash($value);
        }

        return md5(json_encode($value));
    }
}
