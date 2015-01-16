<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder\Util;

/**
 * Simple flags util helper for handling bitwise operators.
 *
 * @package Netzmacht\JavascriptBuilder\Util
 */
class Flags
{
    /**
     * Check if a flag is used.
     *
     * @param int $flag  The flag.
     * @param int $flags The set of flags.
     *
     * @return bool
     */
    public static function contains($flag, $flags)
    {
        return ($flags & $flag) === $flag;
    }

    /**
     * Add a flag to a set of flags.
     *
     * @param int $flag  The flag.
     * @param int $flags The set of flags.
     *
     * @return int
     */
    public static function add($flag, $flags)
    {
        return $flags | $flag;
    }

    /**
     * Remove a flag.
     *
     * @param int $flag  The flag.
     * @param int $flags The set of flags.
     *
     * @return int
     */
    public static function remove($flag, $flags)
    {
        return ($flags & ~$flag);
    }
}
