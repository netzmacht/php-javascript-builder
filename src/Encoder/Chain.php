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
     * Call the next subscriber.
     *
     * @param ChainNode $current   The current chain node.
     * @param string    $method    The method name.
     * @param array     $arguments Method arguments.
     *
     * @return mixed
     */
    public function next(ChainNode $current, $method, array $arguments = array());

    /**
     * Check if an next entry exists for the method.
     *
     * @param ChainNode $current The current chain node.
     * @param string    $method  The method name.
     *
     * @return bool
     */
    public function hasNext(ChainNode $current, $method);

    /**
     * Call the first subscriber.
     *
     * @param string $method    The method name.
     * @param array  $arguments Method arguments.
     *
     * @return mixed
     */
    public function first($method, array $arguments = array());
}
