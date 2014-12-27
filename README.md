PHP Javascript builder library
==============================

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
