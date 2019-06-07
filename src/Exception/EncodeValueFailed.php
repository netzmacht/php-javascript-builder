<?php

/**
 * PHP Javascript Builder
 *
 * @package    netzmacht/php-javascript-builder
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/php-javascript-builder/blob/master/LICENSE
 * @filesource
 */

namespace Netzmacht\JavascriptBuilder\Exception;

use Exception;
use Netzmacht\JavascriptBuilder\Encoder;

/**
 * Class EncodeValueFailed is thrown if no javascript value could be created.
 *
 * @package Netzmacht\JavascriptBuilder\Exception
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
     * Construct.
     *
     * @param mixed     $value    The value.
     * @param int       $code     The error code.
     * @param Exception $previous The previous exception.
     */
    public function __construct($value, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            sprintf('Encoding of value "%s" failed', var_export($value, true)),
            $code,
            $previous
        );

        $this->value = $value;
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
}
