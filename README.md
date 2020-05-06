[View on Packagist](https://packagist.org/packages/rogerthomas84/ohconsole)

OhConsole
=========

OhConsole is a simple interface to create advanced PHP CLI applications by abstracting the commands into separated
classes.

Theory
-----------

Arguments are converted to namespaces. The final route class must be named `<your name>Route`;

For example, the command `config get url` would look for a class of `Config\Get\UrlRoute`

Quick Start
-----------

To get started in a hurry, first create your core command file. Generally this could be `bin/console`.
This file should contain something like this:

```php
<?php
include 'vendor/autoload.php'; // Your autoloader.

use OhConsole\OhConsole;
// Map any injectables that you want to pass
$injectables = array(
    'config' => array(
        // Put any configuration here.
    )
);

// Start OhConsole
$command = new OhConsole($argv, $injectables);

// Run OhConsole
$command->run();
```




Examples
--------

* [Example Command One](/OhConsole/Examples/ExampleOneRoute.php)
* [Example Command Two](/OhConsole/Examples/ExampleTwoRoute.php)

Once you've got your example set up, call your main script (see below) and pass one of the commands defined in the PHP
files.

```sh
php bin/console ohconsole examples one
php bin/console ohconsole examples two
```

Here's a sample `script` that you can use to get started. Feel free to replace the `include`'s with your 
autoloader instead.

```php
<?php
include 'vendor/autoload.php'; // Your autoloader.

use OhConsole\OhConsole;

include 'OhConsole/Examples/ExampleOneRoute.php';
include 'OhConsole/Examples/ExampleTwoRoute.php';

$injectables = array(
    'config' => array(
        'db.name' => 'MyDatabaseName',
        'db.user' => 'root',
        'db.password' => 'password',
    )
);

$command = new OhConsole($argv, $injectables);
$command->run();
```
