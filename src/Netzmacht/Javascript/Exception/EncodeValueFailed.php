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
use Netzmacht\Javascript\Encoder;

/**
 * Class EncodeValueFailed is thrown if no javascript value could be created.
 *
 * @package Netzmacht\Javascript\Exception
 */
class EncodeValueFailed extends \Exception
{
    /**
     * The value.
     *
     * @var mixed
     */
    private $value;

    /**
     * The reference flag.
     *
     * @var bool
     */
    private $reference;

    /**
     * Construct.
     *
     * @param mixed     $value     The value.
     * @param int       $reference The referenced flag.
     * @param int       $code      The error code.
     * @param Exception $previous  The previous exception.
     */
    public function __construct($value, $reference = Encoder::VALUE_DEFINE, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            sprintf('Encoding of value "%s" failed', var_export($value, true)),
            $code,
            $previous
        );

        $this->reference = $reference;
        $this->value     = $value;
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the reference flag.
     *
     * @return int
     */
    public function getReference()
    {
        return $this->reference;
    }
}
