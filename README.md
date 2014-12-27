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

use Netzmacht\Javascript\Encoder;
use Netzmacht\Javascript\Builder;
use Netzmacht\Javascript\Subscriber;
use Netzmacht\Javascript\Subscriber\EncoderSubscriber;
use Netzmacht\Javascript\Type\ConvertsToJavascript;
use Symfony\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new EncoderSubscriber());

$builder  = new Encoder($dispatcher);
$compiler = new Builder($builder, $dispatcher);

class Name implements ConvertsToJavascript
{
    public $firstName;

    public $lastName;
    
    public function encode(Encoder $builder, $finish = true)
    {
        return $builder->encodeValue(array('firstName' => $this->firstName, 'lastName' => $this->lastName));
    }
}

$test = new Name();
$test->firstName = 'Max';
$test->lastName  = 'Mustermann';

echo $compiler->build($test);
```

Instead you integrating the encoding into the object you can also implement an event subscriber.


Requirements
------------

This library uses an event driven architecture by using the 
[symfony event dispatcher](https://github.com/symfony/EventDispatcher).
If you want to compile/build your javascript you have to create some event subscribers/listeners.
