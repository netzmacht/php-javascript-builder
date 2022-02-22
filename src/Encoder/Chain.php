<?php

/**
 * This file is part of the netzmacht/php-javascript-builder class
 *
 * @package    php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2022 netzmacht creative David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
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
