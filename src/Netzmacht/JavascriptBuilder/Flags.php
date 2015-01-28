<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\JavascriptBuilder;

/**
 * Simple flags util helper for handling bitwise operators.
 *
 * @package Netzmacht\JavascriptBuilder\Util
 */
class Flags
{
    /**
     * Close statement flag.
     *
     * Pass it if you want to close the statement when encoding.
     *
     * Note: The flags are also passed to the json_encode method. That's why a high number is chosen. Potentially
     * have to be changed in the future.
     */
    const CLOSE_STATEMENT = 8192;

    /**
     * Build stack flag.
     *
     * If the flag is set the encoder should try to get the object stack first before encoding it. It's usually
     * recommend to set this flag and provide the stack information to avoid nested methods call limit.
     */
    const BUILD_STACK = 16384;

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
        return ($flags | $flag);
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
