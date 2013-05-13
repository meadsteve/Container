Container
=========

Very basic dependency injection container.

Build status
------------

| branch | status |
| ------ | ------ |
| master | [![Build Status](https://travis-ci.org/meadsteve/Container.png?branch=master)](https://travis-ci.org/meadsteve/Container) |

Example Usage
=========

Setup a resource in the container:

```php
use \Meadsteve\Container\Container;
use \Meadsteve\Container\Singleton;

$MyContainer = new Container();

$MyContainer->DependancyOne = new Dependancy();
$MyContainer->MyObject = function(Container $Container) {
	return new MyObject($Container->DependancyOne);
};
```
Then when you need an instance of MyObject:

```php
$InstanceOfMyObject = $MyContainer->MyObject;
```

For some heavy objects you may not want to run the construction logic each time. Then you may want to use the singleton pattern. This is possible with the provided class:

```php
$MyContainer->DBUser = "DBGuy";
$MyContainer->Password = "SuperSecret10";
$MyContainer->DBBasedObject = new Singleton(function(Container $Container) {
	return new DBObject($Container->DBUser, $Container->Password);
});
```

Which is then retrieved in exactly the same way:
```php
$DBInstance = $MyContainer->DBBasedObject
```



