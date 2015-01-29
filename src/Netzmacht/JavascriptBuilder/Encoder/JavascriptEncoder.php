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
use Netzmacht\JavascriptBuilder\Encoder\Chain\StandaloneChain;
use Netzmacht\JavascriptBuilder\Exception\EncodeValueFailed;
use Netzmacht\JavascriptBuilder\Output;
use Netzmacht\JavascriptBuilder\Type\HasStackInformation;
use Netzmacht\JavascriptBuilder\Type\ReferencedByIdentifier;
use Netzmacht\JavascriptBuilder\Type\ConvertsToJavascript;
use Netzmacht\JavascriptBuilder\Flags;

/**
 * Class Encoder provides methods to encode javascript for several input types.
 *
 * @package Netzmacht\Javascript
 */
class JavascriptEncoder extends AbstractChainNode
{
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
     * Construct.
     *
     * @param Output   $output The generated output.
     * @param int|null $flags  The json encode flags.
     */
    public function __construct(Output $output, $flags = null)
    {
        $this->output = $output;
        $this->flags  = $flags;
        $this->chain  = new StandaloneChain($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedMethods()
    {
        return array(
            'setFlags',
            'getFlags',
            'getOutput',
            'encodeValue',
            'encodeArguments',
            'encodeArray',
            'encodeReference',
            'encodeScalar',
            'encodeObject',
            'close',
        );
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
        return ValueHelper::routeEncodeValue($this->chain, $value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArguments(array $arguments, $flags = null)
    {
        $values = array();
        $flags  = Flags::remove(Flags::CLOSE_STATEMENT, $flags);

        foreach ($arguments as $value) {
            if (ValueHelper::isScalar($value) || $value instanceof \JsonSerializable) {
                $values[] = $this->chain->first('encodeScalar')->encodeScalar($value, $flags);
                continue;
            }

            $ref = $this->chain->first('encodeReference')->encodeReference($value);

            if ($ref) {
                $values[] = $ref;
            } else {
                $values[] = $this->chain->first('encodeValue')->encodeValue($value, $flags);
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

            $value = $this->chain->first('encodeReference')->encodeReference($value)
                ?: $this->chain->first('encodeValue')->encodeValue($value);

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
     * @throws EncodeValueFailed If a value could not being encoded.
     */
    public function encodeObject($value, $flags = null)
    {
        if ($value instanceof ConvertsToJavascript) {
            return $value->encode($this->chain->getEncoder(), $flags);
        } elseif ($value instanceof \JsonSerializable) {
            return $this->chain->first('encodeScalar')->encodeScalar($value, $flags);
        }

        throw new EncodeValueFailed($value);
    }

    /**
     * {@inheritdoc}
     */
    public function close($flags)
    {
        return Flags::contains(Flags::CLOSE_STATEMENT, $flags) ? ';' : '';
    }

    /**
     * Get the stack of to encoded objects.
     *
     * @param object $value The object value.
     *
     * @return array
     */
    public function getObjectStack($value)
    {
        if ($value instanceof HasStackInformation) {
            return $value->getObjectStack();
        }

        if ($this->chain->hasNext(__FUNCTION__)) {
            return $this->chain->next(__FUNCTION__)->getObjectStack($value);
        }

        return array();
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
