<?php

namespace spec\Netzmacht\JavascriptBuilder\Type;

use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AnonymousFunctionSpec extends ObjectBehavior
{
    protected static $arguments = array('foo', 'bar', 'test');

    function let()
    {
        $this->beConstructedWith(static::$arguments);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\AnonymousFunction');
    }

    function it_converts_to_javascript()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Type\ConvertsToJavascript');
    }

    function it_has_argument_names()
    {
        $this->getArgumentNames()->shouldReturn(static::$arguments);
    }

    function it_adds_line()
    {
        $this->addLine('test')->shouldReturn($this);
        $this->getLines()->shouldReturn(array('test'));
    }

    function it_adds_lines()
    {
        $this->addLines(array('foo', 'bar'))->shouldReturn($this);
        $this->getLines()->shouldReturn(array('foo', 'bar'));
    }

    function it_encodes()
    {
        $encoder = new JavascriptEncoder(new Output());

        $this->encode($encoder)->shouldReturn('function(foo, bar, test) {  }');
        $this->encode($encoder, Flags::CLOSE_STATEMENT)->shouldReturn('function(foo, bar, test) {  };');

        $this->addLine('alert("test");');
        $this->encode($encoder)->shouldReturn('function(foo, bar, test) { alert("test"); }');

        $this->addLine('alert("test2");');
        $this->encode($encoder)->shouldReturn(
            'function(foo, bar, test) { alert("test");' . "\n" . 'alert("test2"); }'
        );
    }
}
