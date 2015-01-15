<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Javascript;

use Netzmacht\Javascript\Event\EncodeValueEvent;
use Netzmacht\Javascript\Event\GetReferenceEvent;
use Netzmacht\Javascript\Exception\EncodeValueFailed;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class Encoder provides methods to encode javascript for several input types.
 *
 * @package Netzmacht\Javascript
 */
class Encoder
{
    /**
     * List of native values.
     *
     * @var array
     */
    private static $native = array('string', 'integer', 'double', 'NULL', 'boolean');

    /**
     * Values cache.
     *
     * @var array
     */
    private $values = array();

    /**
     * References cache.
     *
     * @var array
     */
    private $references = array();

    /**
     * Json encoding flags.
     *
     * @var int
     */
    private $jsonEncodeFlags;

    /**
     * The output object.
     *
     * @var Output
     */
    private $output;

    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * Construct.
     *
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     * @param int|null        $jsonEncodeFlags The json encode flags.
     */
    public function __construct(EventDispatcher $eventDispatcher, $jsonEncodeFlags = null)
    {
        $this->dispatcher      = $eventDispatcher;
        $this->jsonEncodeFlags = $jsonEncodeFlags;
    }

    /**
     * Get the event dispatcher.
     *
     * @return EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
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
     * Encode an value and return it.
     *
     * @param mixed  $value  The value being encoded.
     * @param Output $output The output being generated.
     *
     * @return string
     * @throws EncodeValueFailed If value could not be encoded.
     */
    public function encode($value, Output $output = null)
    {
        $this->output = $output ?: new Output();
        $this->output->append($this->encodeValue($value));

        return $this->output->getBuffer();
    }

    /**
     * Encode a value and return it's javascript representation.
     *
     * @param mixed $value The generated javascript.
     *
     * @return string
     * @throws EncodeValueFailed If value could not be built.
     */
    public function encodeValue($value)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->values)) {
            if (in_array(gettype($value), static::$native)) {
                // If we got a scalar value, just encode it.
                $this->values[$hash] = $this->encodeScalar($value);

            } elseif (is_array($value)) {
                $this->values[$hash] = $this->encodeArray($value);
            } else {
                $this->values[$hash] = 'blub';

                $event = new EncodeValueEvent($this, $value);
                $this->dispatcher->dispatch($event::NAME, $event);

                if (!$event->isSuccessful()) {
                    throw new EncodeValueFailed($value);
                }

                $this->values[$hash] = $event->getResult();
            }
        }

        return $this->values[$hash];
    }

    /**
     * Encode method call arguments.
     *
     * @param array $arguments The method arguemnts.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not be encoded.
     */
    public function encodeArguments(array $arguments)
    {
        $values = array();

        foreach ($arguments as $value) {
            if (is_callable($value)) {
                $values[] = $this->encodeScalar($value);
            }

            $ref = $this->encodeReference($value);

            if ($ref) {
                $values[] = $ref;
            } else {
                $values[] = $this->encodeValue($value);
            }
        }

        return implode(', ', $values);
    }

    /**
     * Encode the values of an array.
     *
     * @param array $data The array being encoded.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not be encoded.
     */
    public function encodeArray(array $data)
    {
        $buffer  = '';
        $numeric = !(($this->jsonEncodeFlags & JSON_FORCE_OBJECT) == JSON_FORCE_OBJECT);

        if ($numeric) {
            foreach (array_keys($data) as $key) {
                if (!is_numeric($key)) {
                    $numeric = false;
                    break;
                }
            }
        }

        foreach ($data as $key => $value) {
            if (strlen($buffer)) {
                $buffer .= ', ';
            }

            $value = $this->encodeReference($value) ?: $this->encodeValue($value);

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
     * Get a reference to a value. Create the value if not being build so far.
     *
     * @param mixed $value The value being referenced.
     *
     * @return string
     * @throws EncodeValueFailed If a value could not being encoded.
     */
    public function encodeReference($value)
    {
        $hash = $this->hash($value);

        if (!array_key_exists($hash, $this->references)) {
            $event = new GetReferenceEvent($value);
            $this->dispatcher->dispatch($event::NAME, $event);

            $this->references[$hash] = $event->getReference();

            if (!array_key_exists($hash, $this->values) && $this->references[$hash]) {
                $this->output->append($this->encodeValue($value));
            }
        }

        return $this->references[$hash];
    }

    /**
     * Encode a native value.
     *
     * @param mixed $value The scalar value.
     *
     * @return string
     */
    public function encodeScalar($value)
    {
        return json_encode($value, $this->jsonEncodeFlags);
    }

    /**
     * Close a statement depending on the value.
     *
     * @param bool $close If true a semicolon is added.
     *
     * @return string
     */
    public function close($close)
    {
        return $close ? ';' : '';
    }

    /**
     * Create an unique hash of a value.
     *
     * @param mixed $value The value.
     *
     * @return string
     */
    private function hash($value)
    {
        if (is_object($value)) {
            return spl_object_hash($value);
        }

        return md5(json_encode($value));
    }
}
