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

```php

$ php composer.phar require netzmacht/php-javscript-builder:~1.0
```

Requirements
------------

This library uses an event driven architecture by using the 
[symfony event dispatcher](https://github.com/symfony/EventDispatcher).
If you want to compile/build your javascript you have to create some event subscribers/listeners.
