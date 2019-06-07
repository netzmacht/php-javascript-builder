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
use Netzmacht\JavascriptBuilder\Flags;

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
            'encodeReference'
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

        return $this->chain->next($this, __FUNCTION__, [$value, $flags]);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->values)) {
            if (Flags::contains(Flags::BUILD_STACK, $flags)) {
                $flags = Flags::remove(Flags::BUILD_STACK, $flags);
                $flags = Flags::add(Flags::CLOSE_STATEMENT, $flags);

                $this->buildStack($value, $flags);
            }

            $this->values[$hash] = null;
            $this->values[$hash] = $this->chain->next($this, __FUNCTION__, [$value, $flags]);
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
            $this->references[$hash] = $this->chain->next($this, __FUNCTION__, [$value]);

            if ($this->references[$hash] && !array_key_exists($hash, $this->values)) {
                $encoder->getOutput()->append(
                    $encoder->encodeObject(
                        $value,
                        Flags::add(Flags::CLOSE_STATEMENT, $encoder->getFlags())
                    )
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
