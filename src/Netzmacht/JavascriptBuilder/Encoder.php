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

use Netzmacht\JavascriptBuilder\Exception\EncodeValueFailed;

/**
 * Interface Encoder describes provided methods of the javascript encoder.
 *
 * @package Netzmacht\Javascript
 */
interface Encoder
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
     * Get the output.
     *
     * @return Output
     */
    public function getOutput();

    /**
     * Set the encoder flags.
     *
     * @param int $flags The encoder flags.
     *
     * @return $this
     */
    public function setFlags($flags);

    /**
     * Get the encoder flags.
     *
     * @return int
     */
    public function getFlags();

    /**
     * Encode a value and return it's javascript representation.
     *
     * @param mixed    $value The generated javascript.
     * @param int|null $flags Force custom json encode flags.
     *
     * @return string
     * @throws EncodeValueFailed If value could not be built.
     */
    public function encodeValue($value, $flags = null);

    /**
     * Encode method call arguments.
     *
     * @param array    $arguments The method arguments.
     * @param int|null $flags     Force flags are passed to each encoded argument.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not be encoded.
     */
    public function encodeArguments(array $arguments, $flags = null);

    /**
     * Encode the values of an array.
     *
     * @param array    $data  The array being encoded.
     * @param int|null $flags Allow to modify json flags for the array here. Only support JSON_FORCE_OBJECT atm.
     *
     * @return string
     * @throws EncodeValueFailed If value could not be encoded.
     */
    public function encodeArray(array $data, $flags = null);

    /**
     * Get a reference to a value. Create the value if not being build so far.
     *
     * @param mixed $value The value being referenced.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not being encoded.
     */
    public function encodeReference($value);

    /**
     * Encode a native value.
     *
     * @param mixed    $value The scalar value.
     * @param int|null $flags Force custom json encode flags.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not being encoded.
     */
    public function encodeScalar($value, $flags = null);

    /**
     * Encode objects.
     *
     * Be aware that also the resource type could be passed to this method.
     *
     * @param object|resource $value The scalar value.
     * @param int|null        $flags Force flags and pass them to the object encoder.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not being encoded.
     */
    public function encodeObject($value, $flags = null);

    /**
     * Close a statement depending on the value.
     *
     * @param int $flags Close statement if close flag is given.
     *
     * @return string
     */
    public function close($flags);
}
