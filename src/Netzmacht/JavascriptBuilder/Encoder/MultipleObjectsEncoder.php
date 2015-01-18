<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Encoder;

use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Type\HasStackInformation;
use Netzmacht\JavascriptBuilder\Util\Flags;

/**
 * Class MultipleObjectsEncoder allows to generate a whole stack of objects.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
 */
class MultipleObjectsEncoder extends AbstractChainNode
{
    /**
     * List of native values.
     *
     * @var array
     */
    private static $native = array('string', 'integer', 'double', 'NULL', 'boolean');

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
    public function getSubscribedMethods()
    {
        return array(
            'encodeValue',
            'encodeObject',
            'encodeReference',
            'getObjectStack'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function encodeValue($value, $flags = null)
    {
        $hash    = $this->hash($value);
        $encoder = $this->chain->getEncoder();

        if (!array_key_exists($hash, $this->values)) {
            if (in_array(gettype($value), static::$native)) {
                // If we got a scalar value, just encode it.
                $this->values[$hash] = $encoder->encodeScalar($value, $flags);
            } elseif (is_array($value)) {
                $this->values[$hash] = $encoder->encodeArray($value, $flags);
            } else {
                $this->values[$hash] = $encoder->encodeObject($value, $flags);
            }
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
            if (Flags::contains(Encoder::BUILD_STACK, $flags)) {
                $flags = Flags::remove(Encoder::BUILD_STACK, $flags);
                $this->buildStack($value, $flags);
            }

            $this->values[$hash] = null;
            $this->values[$hash] = $this->chain->next(__FUNCTION__)->encodeObject($value, $flags);
        }

        return $this->values[$hash];
    }

    /**
     * {@inheritdoc}
     */
    public function encodeReference($value)
    {
        if (!is_object($value)) {
            return null;
        }

        $hash    = $this->hash($value);
        $encoder = $this->chain->getEncoder();

        if (!array_key_exists($hash, $this->references)) {
            $this->references[$hash] = $this->chain->next(__FUNCTION__)->encodeReference($value);

            if ($this->references[$hash] && !array_key_exists($hash, $this->values)) {
                $encoder->getOutput()->append(
                    $encoder->encodeObject(
                        $value,
                        Flags::add(Encoder::CLOSE_STATEMENT, $encoder->getFlags())
                    )
                );
            }
        }

        return $this->references[$hash];
    }

    /**
     * Get the stack of to encoded objects.
     *
     * @param object $value The object value.
     *
     * @return array
     */
    public function getObjectStack($value)
    {
        if ($value instanceof HasStackInformation) {
            return $value->getObjectStack();
        }

        if ($this->chain->hasNext(__FUNCTION__)) {
            return $this->chain->next(__FUNCTION__)->getObjectStack($value);
        }

        return array();
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

    /**
     * Build object stack to reduce the nested levels of calls.
     *
     * @param mixed $value The value.
     * @param int   $flags The flags.
     *
     * @return void
     */
    private function buildStack($value, $flags)
    {
        $encoder = $this->chain->getEncoder();
        $stack   = $encoder->getObjectStack($value);
        $output  = $encoder->getOutput();

        foreach ($stack as $item) {
            $output->append($encoder->encodeObject($item, $flags));
        }
    }
}
