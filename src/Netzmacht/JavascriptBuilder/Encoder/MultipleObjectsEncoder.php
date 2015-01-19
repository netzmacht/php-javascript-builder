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
        if (is_object($value)) {
            $hash = $this->hash($value);

            if (array_key_exists($hash, $this->values)) {
                return $this->values[$hash];
            }
        }

        return $this->chain->next(__FUNCTION__)->encodeValue($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->values)) {
            $next = $this->chain->next(__FUNCTION__);

            if (Flags::contains(Encoder::BUILD_STACK, $flags)) {
                $flags = Flags::remove(Encoder::BUILD_STACK, $flags);
                $flags = Flags::add(Encoder::CLOSE_STATEMENT, $flags);

                $this->buildStack($value, $flags);
            }

            $this->chain->jumpTo(__FUNCTION__, $next);

            $this->values[$hash] = null;
            $this->values[$hash] = $next->encodeObject($value, $flags);
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
        $output  = $encoder->getOutput();

        $stack = $encoder->getObjectStack($value);
        $built = array();

        foreach ($stack as $item) {
            if (!in_array($item, $built)) {
                $output->append($encoder->encodeObject($item, $flags));
                $built[] = $item;
            }
        }
    }
}
