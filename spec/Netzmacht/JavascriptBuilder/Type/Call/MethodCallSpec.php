<?php

namespace spec\Netzmacht\JavascriptBuilder\Type\Call;

use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use Netzmacht\JavascriptBuilder\Type\ReferencedByIdentifier;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodCallSpec extends ObjectBehavior
{
    const NAME = 'foo';

    protected static $arguments = array('bar');

    function let(reference $reference)
    {
        $this->beConstructedWith($reference, self::NAME, static::$arguments);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\Call\FunctionCall');
    }

    function it_converts_to_javascript()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\ConvertsToJavascript');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn(self::NAME);
    }

    function it_has_an_object(reference $reference)
    {
        $this->getObject()->shouldReturn($reference);
    }

    function it_has_arguments()
    {
        $this->getArguments()->shouldHaveType('Netzmacht\JavascriptBuilder\Type\Arguments');
        $this->getArguments()->getArguments()->shouldReturn(static::$arguments);
    }

    function it_encodes(reference $reference)
    {
        $reference->getReferenceIdentifier()->willReturn('ref');
        $encoder = new JavascriptEncoder(new Output());

        $this->encode($encoder)->shouldReturn('ref.foo("bar")');
        $this->encode($encoder, Flags::CLOSE_STATEMENT)->shouldReturn('ref.foo("bar");');
    }
}

interface reference extends ReferencedByIdentifier
{

}
