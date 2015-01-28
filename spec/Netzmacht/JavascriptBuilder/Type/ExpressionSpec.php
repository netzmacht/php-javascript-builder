<?php

namespace spec\Netzmacht\JavascriptBuilder\Type;

use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpressionSpec extends ObjectBehavior
{
    const EXPR = 'expr';

    function let()
    {
        $this->beConstructedWith(static::EXPR);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\Expression');
    }

    function it_converts_to_javascript()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\ConvertsToJavascript');
    }

    function it_converts_to_string()
    {
        $this->__toString()->shouldReturn(static::EXPR);
    }

    function it_encodes()
    {
        $encoder = new JavascriptEncoder(new Output());

        $this->encode($encoder)->shouldReturn(static::EXPR);
        $this->encode($encoder, Flags::CLOSE_STATEMENT)->shouldReturn(static::EXPR . ';');
    }
}
