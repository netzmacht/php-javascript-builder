<?php

namespace spec\Netzmacht\JavascriptBuilder\Type\Call;

use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FunctionCallSpec extends ObjectBehavior
{
    const NAME = 'foo';

    protected static $arguments = array('bar');

    function let()
    {
        $this->beConstructedWith(self::NAME, static::$arguments);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\Call\FunctionCall');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn(self::NAME);
    }

    function it_has_arguments()
    {
        $this->getArguments()->shouldHaveType('Netzmacht\JavascriptBuilder\Type\Arguments');
        $this->getArguments()->getArguments()->shouldReturn(static::$arguments);
    }

    function it_encodes()
    {
        $encoder = new JavascriptEncoder(new Output());

        $this->encode($encoder)->shouldReturn('foo("bar")');
        $this->encode($encoder, Flags::CLOSE_STATEMENT)->shouldReturn('foo("bar");');
    }
}
