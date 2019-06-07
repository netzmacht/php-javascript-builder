<?php

namespace spec\Netzmacht\JavascriptBuilder\Encoder;

use Netzmacht\JavascriptBuilder\Encoder\AbstractChainNode;
use Netzmacht\JavascriptBuilder\Encoder\Chain;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractChainNodeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\Netzmacht\JavascriptBuilder\Encoder\ChainNode');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Encoder\AbstractChainNode');
    }

    function it_is_a_chain_node()
    {
        $this->shouldImplement('Netzmacht\JavascriptBuilder\Encoder\ChainNode');
    }

    function it_sets_chain(Chain $chain)
    {
        $this->setChain($chain)->shouldReturn($this);
    }

    function it_throws_for_not_supported()
    {
        $this->shouldThrow('BadMethodCallException')->during('setFlags', array(1));
        $this->shouldThrow('BadMethodCallException')->during('getFlags');
        $this->shouldThrow('BadMethodCallException')->during('getOutput');
        $this->shouldThrow('BadMethodCallException')->during('encodeValue', array('test'));
        $this->shouldThrow('BadMethodCallException')->during('encodeArguments', array(array()));
        $this->shouldThrow('BadMethodCallException')->during('encodeArray', array(array()));
        $this->shouldThrow('BadMethodCallException')->during('encodeReference', array(new \stdClass()));
        $this->shouldThrow('BadMethodCallException')->during('encodeScalar', array(1));
        $this->shouldThrow('BadMethodCallException')->during('encodeObject', array(new \stdClass()));
        $this->shouldThrow('BadMethodCallException')->during('close', array(1));
    }
}

class ChainNode extends AbstractChainNode
{
    /**
     * Get a list of the subscribed methods.
     *
     * @return array
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
}
