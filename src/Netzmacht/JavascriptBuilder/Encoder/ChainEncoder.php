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
 * Class ChainEncoder is an encoder who delegates all encoding requests to the used chain.
 *
 * @package Netzmacht\JavascriptBuilder\Encoder
 */
class ChainEncoder implements Encoder, Chain
{
    /**
     * Registered method subscribers.
     *
     * @var ChainNode[][]
     */
    private $methods = array();

    /**
     * The current position for subscribed methods.
     *
     * @var array
     */
    private $current = array();

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        return $this->first(__FUNCTION__)->getOutput();
    }

    /**
     * {@inheritdoc}
     */
    public function setFlags($flags)
    {
        return $this->first(__FUNCTION__)->setFlags($flags);
    }

    /**
     * {@inheritdoc}
     */
    public function getFlags()
    {
        return $this->first(__FUNCTION__)->getFlags();
    }

    /**
     * {@inheritdoc}
     */
    public function encodeValue($value, $flags = null)
    {
        return ValueHelper::routeEncodeValue($this, $value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArguments(array $arguments, $flags = null)
    {
        return $this->first(__FUNCTION__)->encodeArguments($arguments, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeArray(array $data, $flags = null)
    {
        return $this->first(__FUNCTION__)->encodeArray($data, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeReference($value)
    {
        return $this->first(__FUNCTION__)->encodeReference($value);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeScalar($value, $flags = null)
    {
        return $this->first(__FUNCTION__)->encodeScalar($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function encodeObject($value, $flags = null)
    {
        return $this->first(__FUNCTION__)->encodeObject($value, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function close($flags)
    {
        return $this->first(__FUNCTION__)->close($flags);
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectStack($value)
    {
        return $this->first(__FUNCTION__)->getObjectStack($value);
    }

    /**
     * Get the chain encoder.
     *
     * @return Encoder
     */
    public function getEncoder()
    {
        return $this;
    }

    /**
     * Register a chain node.
     *
     * @param ChainNode $node The chain node.
     *
     * @return $this
     */
    public function register(ChainNode $node)
    {
        $node->setChain($this);

        foreach ($node->getSubscribedMethods() as $method) {
            $this->methods[$method][] = $node;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function next($method)
    {
        if (!isset($this->current[$method])) {
            return $this->first($method);
        }

        $index = ++$this->current[$method];

        $this->guardMethodExists($method);
        $this->guardSubscriberExists($method, $index);

        return $this->methods[$method][$index];
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext($method)
    {
        if (empty($this->methods[$method])) {
            return false;
        }

        if (array_key_exists($method, $this->current)) {
            $key = ($this->current[$method] + 1);
        } else {
            $key = 0;
        }

        return isset($this->methods[$method][$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function first($method)
    {
        $this->guardMethodExists($method);
        $this->guardSubscriberExists($method, 0);

        $this->current[$method] = 0;

        return $this->methods[$method][0];
    }

    /**
     * {@inheritdoc}
     * @throws \BadMethodCallException If jumpTo point does not exists.
     */
    public function jumpTo($method, ChainNode $subscriber)
    {
        $this->guardMethodExists($method);

        foreach ($this->methods[$method] as $index => $item) {
            if ($item === $subscriber) {
                $this->current[$method] = $index;

                return $this->methods[$method][$index];
            }
        }

        throw new \BadMethodCallException(
            sprintf('Could not find subscriber as registered subscribers of method call "%s".', $method)
        );
    }

    /**
     * Guard that a method is subscribed.
     *
     * @param string $method The method name.
     *
     * @return void
     * @throws \BadMethodCallException If no subscribers were found.
     */
    private function guardMethodExists($method)
    {
        if (!array_key_exists($method, $this->methods)) {
            throw new \BadMethodCallException(sprintf('No subscribers found for method call"%s"', $method));
        }
    }

    /**
     * Guard that a subscriber at an index exists.
     *
     * @param string $method The method name.
     * @param int    $index  The subscriber index.
     *
     * @return void
     * @throws \BadMethodCallException If the subscriber index is out of bounds.
     */
    private function guardSubscriberExists($method, $index)
    {
        if (!isset($this->methods[$method][$index])) {
            throw new \BadMethodCallException(
                sprintf(
                    'End of subscriber chain. No more subscribers found for method call "%s"',
                    $method
                )
            );
        }
    }
}
