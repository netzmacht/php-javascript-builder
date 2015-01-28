<?php

namespace spec\Netzmacht\JavascriptBuilder\Encoder;

use Assert\Tests\ChildStdClass;
use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Encoder\Chain;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use Netzmacht\JavascriptBuilder\Type\ConvertsToJavascript;
use Netzmacht\JavascriptBuilder\Type\ReferencedByIdentifier;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class JavascriptEncoderSpec
 * @package spec\Netzmacht\JavascriptBuilder\Encoder
 */
class JavascriptEncoderSpec extends ObjectBehavior
{
    function let(Output $output)
    {
        $this->beConstructedWith($output);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder');
    }

    function it_encodes_scalars()
    {
        $this
            ->supportsScalar('test', '"test"')
            ->supportsScalar(5, '5')
            ->supportsScalar(5.5, '5.5')
            ->supportsScalar(null, 'null')
            ->supportsScalar(false, 'false')
            ->supportsScalar(true, 'true');
    }

    function it_encodes_arrays()
    {
        $data = array('foo' => 'bar');
        $this->supportsArrays($data, '{foo: "bar"}');

        $data = array('foo' => null);
        $this->supportsArrays($data, '{foo: null}');

        $data = array('foo', 'bar');
        $this->supportsArrays($data, '["foo", "bar"]');

        $data = array('foo', 'bar');
        $this->supportsArrays($data, '{"foo", "bar"}', JSON_FORCE_OBJECT);
    }

    function it_encodes_reference(ReferencedByIdentifier $identifier)
    {
        $identifier->getReferenceIdentifier()->willReturn('test');

        $this->encodeReference($identifier)->shouldReturn('test');
    }

    function it_encodes_arguments(ReferencedByIdentifier $identifier)
    {
        $identifier->getReferenceIdentifier()->willReturn('test');

        $args = array('foo', true, false, null, 1, 1.54, $identifier);

        $this->encodeArguments($args)->shouldReturn('"foo", true, false, null, 1, 1.54, test');
    }

    function it_encodes_object(ConvertsToJavascript $toJavascript)
    {
        $toJavascript->encode($this, null)->willReturn('alert("ok")');
        $toJavascript->encode($this, Flags::CLOSE_STATEMENT)->willReturn('alert("ok");');

        $this->encodeValue($toJavascript)->shouldReturn('alert("ok")');
        $this->encodeValue($toJavascript, Flags::CLOSE_STATEMENT)->shouldReturn('alert("ok");');
    }

    function it_closes_statement()
    {
        $this->close(Flags::CLOSE_STATEMENT)->shouldReturn(';');
        $this->close(JSON_FORCE_OBJECT)->shouldReturn('');
    }

    function it_is_a_node_chain()
    {
        $this->shouldImplement('Netzmacht\JavascriptBuilder\Encoder\ChainNode');
    }

    function it_delegates_encoding_to_chain(Chain $chain, ConvertsToJavascript $toJavascript, Encoder $encoder)
    {
        $this->setChain($chain)->shouldReturn($this);

        $this->delegatesEncoding('test', 'scalar', $chain, $encoder);
        $this->delegatesEncoding($toJavascript, 'object', $chain, $encoder);
        $this->delegatesEncoding(array('foo'), 'array', $chain, $encoder);
    }

    private function supportsScalar($input, $expected)
    {
        $this->encodeValue($input)->shouldReturn($expected);
        $this->encodeScalar($input)->shouldReturn($expected);

        return $this;
    }

    private function supportsArrays($input, $expected, $flags = null)
    {
        $this->encodeValue($input, $flags)->shouldReturn($expected);
        $this->encodeArray($input, $flags)->shouldReturn($expected);

        return $this;
    }

    private function delegatesEncoding($value, $type, Chain $chain, Encoder $encoder)
    {
        $method = 'encode' . ucfirst($type);

        $chain->first($method)->willReturn($encoder);
        $this->encodeValue($value);
    }
}
