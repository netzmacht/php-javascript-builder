<?php

namespace spec\Netzmacht\JavascriptBuilder\Type;

use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArgumentsSpec extends ObjectBehavior
{
    protected static $arguments = array('bar', true, null, 1);

    function let()
    {
        $this->beConstructedWith(static::$arguments);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\Arguments');
    }

    function it_has_arguments()
    {
        $this->getArguments()->shouldReturn(static::$arguments);
    }

    function it_encodes()
    {
        $encoder = new JavascriptEncoder(new Output());

        $this->encode($encoder)->shouldReturn('"bar", true, null, 1');
        $this->encode($encoder, Flags::CLOSE_STATEMENT)->shouldReturn('"bar", true, null, 1');
    }
}
