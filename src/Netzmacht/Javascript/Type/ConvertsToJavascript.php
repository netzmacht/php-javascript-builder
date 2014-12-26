<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Type;

use Netzmacht\Javascript\Builder;

/**
 * Interface ConvertsToJavascript describes an object that can be converted to a javascript representation.
 *
 * @package Netzmacht\Javascript\Type
 */
interface ConvertsToJavascript
{
    /**
     * Build the javscript representation of the object.
     *
     * @param Builder $builder The javascript builder.
     * @param bool    $finish  If true the statement should be finished with an semicolon.
     *
     * @return string
     */
    public function build(Builder $builder, $finish = true);
}
