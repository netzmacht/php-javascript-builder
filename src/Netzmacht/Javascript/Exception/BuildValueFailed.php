<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Exception;


use Exception;

class BuildValueFailed extends \Exception
{
    private $value;

    private $reference;

    public function __construct($value, $reference = false, $code = 0, Exception $previous = null)
    {
        parent::__construct('Build of value failed', $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }
}
