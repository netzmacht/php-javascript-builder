<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript\Encoder;

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Exception\EncodeValueFailed;
use Netzmacht\Javascript\Output;
use Netzmacht\Javascript\Type\ReferencedByIdentifier;
use Netzmacht\Javascript\Type\ConvertsToJavascript;
use Netzmacht\Javascript\Util\Flags;

/**
 * Class Encoder provides methods to encode javascript for several input types.
 *
 * @package Netzmacht\Javascript
 */
class JavascriptEncoder implements ChainNode
{
    /**
     * List of native values.
     *
     * @var array
     */
    private static $native = array('string', 'integer', 'double', 'NULL', 'boolean');

    /**
     * Json encoding flags.
     *
     * @var int
     */
    private $flags;

    /**
     * The output object.
     *
     * @var Output
     */
    private $output;

    /**
     * The root encoder.
     *
     * @var Encoder
     */
    private $encoder;

    /**
     * Construct.
     *
     * @param Output   $output The generated output.
     * @param int|null $flags  The json encode flags.
     */
    public function __construct(Output $output, $flags = null)
    {
        $this->encoder = $this;
        $this->output  = $output;
        $this->flags   = $flags;
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncoder(Encoder $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Get the output.
     *
     * @return Output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * {@inheritdoc}
     */
    public function encodeValue($value, $flags = null)
    {
        if (in_array(gettype($value), static::$native)) {
            // If we got a scalar value, just encode it.
            return $this->encoder->encodeScalar($value, $flags);
        } elseif (is_array($value)) {
            return $this->encoder->encodeArray($value, $flags);
        }

        return $this->encoder->encodeObject($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArguments(array $arguments, $flags = null)
    {
        $values = array();

        foreach ($arguments as $value) {
            if (is_callable($value)) {
                $values[] = $this->encoder->encodeScalar($value, $flags);
            }

            $ref = $this->encoder->encodeReference($value);

            if ($ref) {
                $values[] = $ref;
            } else {
                $values[] = $this->encoder->encodeValue($value, $flags);
            }
        }

        return implode(', ', $values);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArray(array $data, $flags = null)
    {
        $flags   = $this->flags($flags);
        $buffer  = '';
        $numeric = $this->isNumericArray($data, $flags);

        foreach ($data as $key => $value) {
            if (strlen($buffer)) {
                $buffer .= ', ';
            }

            $value = $this->encoder->encodeReference($value) ?: $this->encoder->encodeValue($value);

            if (is_numeric($key)) {
                $buffer .= $value;
            } else {
                $buffer .= ctype_alnum($key) ? $key : ('"' . $key . '"');
                $buffer .= ': ' . $value;
            }
        }

        if ($numeric) {
            return '[' . $buffer . ']';
        } else {
            return '{' . $buffer . '}';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encodeReference($value)
    {
        if ($value instanceof ReferencedByIdentifier) {
            return $value->getReferenceIdentifier();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function encodeScalar($value, $flags = null)
    {
        return json_encode($value, $flags ?: $this->flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        if ($value instanceof ConvertsToJavascript) {
            return $value->encode($this->encoder, $flags);
        } elseif ($value instanceof \JsonSerializable) {
            return $this->encoder->encodeScalar($value, $flags);
        }

        throw new EncodeValueFailed($value);
    }

    /**
     * {@inheritdoc}
     */
    public function close($flags)
    {
        return Flags::contains(static::CLOSE_STATEMENT, $flags) ? ';' : '';
    }

    /**
     * Check if given array is an numeric one.
     *
     * @param array    $data  The array being encoded.
     * @param int|null $flags Optional json encode flags.
     *
     * @return bool
     */
    private function isNumericArray(array $data, $flags)
    {
        if (Flags::contains(JSON_FORCE_OBJECT, $flags)) {
            return false;
        }

        foreach (array_keys($data) as $key) {
            if (!is_numeric($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get used flags.
     *
     * @param int|null $flags Used encoding flags.
     *
     * @return int|null
     */
    private function flags($flags)
    {
        return $flags === null ? $this->flags : $flags;
    }
}
