PHP Javascript builder library
==============================

[![Build Status](http://img.shields.io/travis/netzmacht/php-javascript-builder/master.svg?style=flat-square)](https://travis-ci.org/netzmacht/php-javascript-builder)
[![Version](http://img.shields.io/packagist/v/netzmacht/php-javascript-builder.svg?style=flat-square)](http://packagist.com/packages/netzmacht/php-javascript-builder)
[![License](http://img.shields.io/packagist/l/netzmacht/php-javascript-builder.svg?style=flat-square)](http://packagist.com/packages/netzmacht/php-javascript-builder)
[![Downloads](http://img.shields.io/packagist/dt/netzmacht/php-javascript-builder.svg?style=flat-square)](http://packagist.com/packages/netzmacht/php-javascript-builder)

This library is an event based javascript builder/compiler form PHP 5.4.

The goal of this library is to convert an object definition tree which was created in PHP into Javascript. This is 
useful if you have some dynamically defined javascript libraries.

Install
-------

This library can be installed using composer:

```php

$ php composer.phar require netzmacht/php-javascript-builder:~1.0
```

Usage
------

The easiest way to implement the javascript encoding feature is to implement the `ConvertsToJavascript` interface. Then
the encoder uses the provides `encode` method to encode the object.

See the example below:

```php
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

class Foo implements ConvertsToJavascript
{
    private $bar;

    public function __construct($bar)
    {
        $this->bar = $bar;
    }

    public function encode(Encoder $encoder, $flags = null)
    {
        return 'console.log(' . $encoder->encodeReference($this->bar) . ')' . $encoder->close($flags);
    }
}

class Bar implements ConvertsToJavascript, ReferencedByIdentifier
{
    public function encode(Encoder $encoder, $flags = null)
    {
        return sprintf (
            '%s = new Bar()%s',
            $encoder->encodeReference($this),
            $encoder->close($flags)
        );
    }

    public function getReferenceIdentifier()
    {
        return 'bar';
    }
}

$builder = new Builder();

$bar = new Bar();
$foo = new Foo($bar);

echo '<pre>';
echo $builder->encode($foo);
// bar = new Bar();
// console.log(bar);
```

Custom encoders
---------------

You can also tweak the encoding process by add another encoder to the encoding chain. This library provides an 
implementation of an event dispatching encoder using the symfony/event-dispatcher. Be aware that the event dispatcher
is not installed by default. If you want to use it, install it:

```php

$ php composer.phar require symfony/event-dispatcher:~2.3
```

The builder accepts an encoder factory callable. So you can easily assign other encoders. Be aware that the
ResultCacheEncoder is required so that referenced items get rendered before they are getting referenced. Otherwise
you would only see the `bar.foo();` output of the example above.

```php

// Setup the dispatcher outside so that the listeners can be added.
$dispatcher = new EventDispatcher();
$factory    = function(Output $output) use ($dispatcher) {
    return new ResultCacheEncoder(
        new Netzmacht\JavascriptBuilder\Symfony\EventDispatchingEncoder(
            new JavascriptEncoder($output),
            $dispatcher
        )
    );
};

```

The event dispatching encoder fires two events:
 - `javascript-builder.encode-reference` with an event object of `Netzmacht\JavascriptBuilder\Symfony\Event\EncodeReferenceEvent`
    is triggered when an reference is requested. It's called before the `ReferencedByIdentifier` is checked.
    
 - `javascript-builder.encode-value` with an event object of `Netzmacht\JavascriptBuilder\Symfony\Event\EncodeReferenceEvent`
    is triggered when an object value is being created. It's called before the default implementation checks for the
    `ConvertsToJavascript` interface or even the `JsonSerialize`
