<?php

namespace spec\Netzmacht\JavascriptBuilder;

use Netzmacht\JavascriptBuilder\Output;
use PhpSpec\ObjectBehavior;

class OutputSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Output::class);
    }

    function it_appends()
    {
        $this->append('foo')->shouldReturn($this);
        $this->getBuffer()->shouldReturn('foo');

        $this->append('bar')->shouldReturn($this);
        $this->getBuffer()->shouldReturn('foo' . "\n" . 'bar');
    }

    function it_prepends()
    {
        $this->prepend('foo')->shouldReturn($this);
        $this->getBuffer()->shouldReturn('foo');

        $this->prepend('bar')->shouldReturn($this);
        $this->getBuffer()->shouldReturn('bar' . "\n" . 'foo');
    }

    function it_has_a_separator()
    {
        $this->setSeparator('test')->shouldReturn($this);
        $this->getSeparator()->shouldReturn('test');

        $this->append('foo');
        $this->append('bar');
        $this->getBuffer()->shouldReturn('footestbar');
    }

    function it_has_a_default_separator()
    {
        $this->getSeparator()->shouldReturn("\n");
    }

    function it_clears_the_output()
    {
        $this->prepend('foo');
        $this->getBuffer()->shouldReturn('foo');

        $this->clear()->shouldReturn($this);
        $this->getBuffer()->shouldReturn('');
    }

    function it_converts_to_string()
    {
        $this->prepend('foo');
        $this->__toString()->shouldReturn('foo');
    }
}
