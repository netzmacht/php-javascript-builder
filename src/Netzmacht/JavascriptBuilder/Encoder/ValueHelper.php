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

/**
 * ValueHelper adds support for handling values being encoded.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
 */
class ValueHelper
{
    /**
     * List of native values.
     *
     * @var array
     */
    private static $native = array('string', 'integer', 'double', 'NULL', 'boolean');

    /**
     * Encode a value and return it's javascript representation.
     *
     * @param Chain    $chain The chain.
     * @param mixed    $value The generated javascript.
     * @param int|null $flags Force custom json encode flags.
     *
     * @return string
     */
    public static function routeEncodeValue(Chain $chain, $value, $flags = null)
    {
        if (static::isScalar($value)) {
            // If we got a scalar value, just encode it.
            return $chain->first('encodeScalar', [$value, $flags]);
        } elseif (is_array($value)) {
            return $chain->first('encodeArray', [$value, $flags]);
        }

        return $chain->first('encodeObject', [$value, $flags]);
    }

    /**
     * Check if value is a scalar value.
     *
     * @param mixed $value The value.
     *
     * @return bool
     */
    public static function isScalar($value)
    {
        return in_array(gettype($value), self::$native);
    }
}
