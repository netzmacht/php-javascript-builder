<?php

namespace spec\Netzmacht\JavascriptBuilder\Encoder;

use Netzmacht\JavascriptBuilder\Encoder\Chain;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValueHelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Netzmacht\JavascriptBuilder\Encoder\ValueHelper');
    }

    function it_routes_encoding_of_strings_to_scalar_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, 'test', 'encodeScalar');
    }

    function it_routes_encoding_of_integers_to_scalar_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, 1, 'encodeScalar');
    }

    function it_routes_encoding_of_floats_to_scalar_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, 1.4, 'encodeScalar');
    }

    function it_routes_encoding_of_null_to_scalar_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, null, 'encodeScalar');
    }

    function it_routes_encoding_of_boolean_to_scalar_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, true, 'encodeScalar');
        $this->routesEncoding($chain, false, 'encodeScalar');
    }

    function it_routes_encoding_of_array_to_array_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, [], 'encodeArray');
    }

    function it_routes_encoding_of_objects_to_object_encoding(Chain $chain)
    {
        $this->routesEncoding($chain, new \stdClass(), 'encodeObject');
    }

    function it_detects_scalars()
    {
        $this->isScalar('test')->shouldReturn(true);
        $this->isScalar(1)->shouldReturn(true);
        $this->isScalar(1.4)->shouldReturn(true);
        $this->isScalar(null)->shouldReturn(true);
        $this->isScalar(true)->shouldReturn(true);
        $this->isScalar(false)->shouldReturn(true);

        $this->isScalar([])->shouldReturn(false);
        $this->isScalar(new \stdClass())->shouldReturn(false);
    }

    function routesEncoding(Chain $chain, $value, $target)
    {
        $chain->first($target, [$value, null])->shouldBeCalled();;
        $this->routeEncodeValue($chain, $value);
    }
}
