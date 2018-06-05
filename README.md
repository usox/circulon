# Circulon - A PHP dependency resolver

[![Build Status](https://travis-ci.org/usox/circulon.svg?branch=master)](https://travis-ci.org/usox/circulon)

Requirements
============

To use Circulon in your projects, you will just require PHP 5.6 or later.

Composer install
================

You can install this package by using [Composer](http://getcomposer.org).
Link to Packagist: https://packagist.org/packages/usox/circulon

```sh
composer require usox/circulon
```

Usage
=====

Simply add your dependencies as follows:

```php
$resolver = new \Usox\Circulon\Circulon();
$resolver
  ->addDependency('foo', 'bar')
  ->addDependency('foobar', [])
  ->addDependency('baz', [])
  ->addDependency('bar', ['baz', 'foobar']);
```

Calling `resolve()` will return the dependencies in order.

```php
$list = $resolver->resolve();

var_dump($list);

array(4) {
  [0] =>
  string(3) "baz"
  [1] =>
  string(6) "foobar"
  [2] =>
  string(3) "bar"
  [3] =>
  string(3) "foo"
}
```

Circular dependencies
=====================

```php

$resolver
  ->addDependency('foo', 'bar')
  ->addDependency('bar', 'baz')
  ->addDependency('baz', 'foo');

$resolver->resolve();
```

Circulon detects the circular dependency and throws a `CircularDependencyException` with message `Circular reference for baz => foo`.
