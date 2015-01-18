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

/**
 * Interface Chain describes a chain of multiple encoders.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
 */
interface Chain
{
    /**
     * The the outside encoder.
     *
     * @return Encoder
     */
    public function getEncoder();

    /**
     * Create the next subscriber.
     *
     * @param string $method The method name.
     *
     * @return Encoder
     */
    public function next($method);

    /**
     * Check if an next entry exists for the method.
     *
     * @param string $method The method name.
     *
     * @return bool
     */
    public function hasNext($method);

    /**
     * Create the first subscriber.
     *
     * @param string $method The method name.
     *
     * @return Encoder
     */
    public function first($method);
}
