<?php

namespace spec\Netzmacht\JavascriptBuilder;

use Netzmacht\JavascriptBuilder\Builder;
use Netzmacht\JavascriptBuilder\Encoder;
use Netzmacht\JavascriptBuilder\Flags;
use Netzmacht\JavascriptBuilder\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Builder::class);
    }

    function it_encodes_with_default_factory_by_default()
    {
        $this->encode('test')->shouldReturn('"test"');
    }

    function it_accepts_custom_factory(factory $factory, Encoder $encoder)
    {
        $encoder->encodeValue('test', Argument::type('int'))->willReturn('"test"');
        $encoder->getFlags()->willReturn(null);

        $factory->__invoke(Argument::type(Output::class))
            ->shouldBeCalled()
            ->willReturn($encoder);

        $this->setEncoderFactory($factory)->shouldReturn($this);
        $this->encode('test')->shouldReturn('"test"');
    }

    function it_accepts_custom_factory_as_construct_argument(factory $factory, Encoder $encoder)
    {
        $encoder->encodeValue('test', Argument::type('int'))->willReturn('"test"');
        $encoder->getFlags()->willReturn(null);

        $factory->__invoke(Argument::type(Output::class))
            ->shouldBeCalled()
            ->willReturn($encoder);

        $this->beConstructedWith($factory);
        $this->encode('test')->shouldReturn('"test"');
    }

    function it_accepts_a_custom_output(Output $output)
    {
        $output->append('"test"')->shouldBeCalled();
        $output->getBuffer()->shouldBeCalled();

        $this->encode('test', null, $output);
    }
}

interface factory
{
    public function __invoke($output);
}
