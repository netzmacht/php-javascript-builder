<?php

namespace spec\Netzmacht\JavascriptBuilder;

use Netzmacht\JavascriptBuilder\Flags;
use PhpSpec\ObjectBehavior;

class FlagsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Flags::class);
    }

    function it_adds_a_flag()
    {
        $this->add(1, 2)->shouldReturn(3);
        $this->add(1, 1)->shouldReturn(1);
    }

    function it_removes_a_flag()
    {
        $this->remove(1, 3)->shouldReturn(2);
        $this->remove(1, 1)->shouldReturn(0);
    }

    function it_checks_if_flag_is_contained()
    {
        $this->contains(1, 3)->shouldReturn(true);
        $this->contains(1, 2)->shouldReturn(false);
    }
}
