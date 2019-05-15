[View on Packagist](https://packagist.org/packages/rogerthomas84/ohconsole)

OhConsole
=========

OhConsole is a simple interface to create advanced PHP CLI applications by abstracting the commands into separated
classes.

Quick Start
-----------

To get started in a hurry, first create your core command file. Generally this could be `bin/console`.
This file should contain something like this:

```php
<?php
include "autoloader.php"; // Your autoloader.
                          // If you don't have one then you can use the below
/**
   include 'OhConsole/OhConsole.php';
   include 'OhConsole/OhCommand.php';
   include 'OhConsole/Exception/ArgumentNotSetException.php';
   include 'OhConsole/Exception/InvalidConsoleArgumentException.php';
   include 'OhConsole/Exception/NoCommandClassesGivenException.php';
*/

// Map your commands
$classes = array(
    '\OhConsole\Examples\ExampleOne',
    '\OhConsole\Examples\ExampleTwo'
);

// Map any injectables that you want to pass
$injectables = array(
    'config' => array(
        // Put any configuration here.
    )
);

// Start OhConsole
$command = new \OhConsole\OhConsole($argv, $classes, $injectables);

// Run OhConsole
$command->run();
```




Examples
--------

* [Example Command One](/OhConsole/Examples/ExampleOne.php)
* [Example Command Two](/OhConsole/Examples/ExampleTwo.php)

Once you've got your example set up, call your main script (see below) and pass one of the commands defined in the PHP
files.

```sh
php bin/console ohconsole-example-one
php bin/console ohconsole-example-two
```

Here's a sample `script` that you can use to get started. Feel free to replace the `include`'s with your 
autoloader instead.

```php
<?php
include 'OhConsole/OhConsole.php';
include 'OhConsole/OhCommand.php';
include 'OhConsole/Exception/ArgumentNotSetException.php';
include 'OhConsole/Exception/InvalidConsoleArgumentException.php';
include 'OhConsole/Exception/NoCommandClassesGivenException.php';

include 'OhConsole/Examples/ExampleOne.php';
include 'OhConsole/Examples/ExampleTwo.php';

$classes = array(
    '\OhConsole\Examples\ExampleOne',
    '\OhConsole\Examples\ExampleTwo'
);

$injectables = array(
    'config' => array(
        'db.name' => 'MyDatabaseName',
        'db.user' => 'root',
        'db.password' => 'password',
    )
);

$command = new \OhConsole\OhConsole($argv, $classes, $injectables);
$command->run();
```
