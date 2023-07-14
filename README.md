[View on Packagist](https://packagist.org/packages/rogerthomas84/ohconsole)

District5\Cli
=========

Originally from the [OhConsole](https://github.com/rogerthomas84/ohconsole) project.

Cli is a simple interface to create advanced PHP CLI applications by abstracting the commands into separated
classes.

Composer
-----------

```json
{
    "repositories":[
        {
            "type": "vcs",
            "url": "git@github.com:district-5/php-cli.git"
        }
    ],
    "require": {
        "district-5/cli": "*"
    }
}
```

Theory
-----------

Arguments get converted to namespaces. The final route class must be appended with `Route`. So for example, a fully
qualified class name of `SomeApp\Foo\Bar\ProcessMyTasksRoute` could be called with `some-app foo bar process-my-tasks`

A much simpler example would be `Config\Get\UrlRoute` which would be called with `config get url`

Quick Start
-----------

To get started in a hurry, first create your core command file. Generally this could be `bin/console`.
This file should contain something like this:

```php
<?php
use District5\Cli\CliApp;

// Map any injectables that you want to pass
$injectables = [
    'config' => [
        // Put any configuration here.
    ]
];

// Start CliApp
$cliApp = CliApp::createApp($argv, $injectables); // or `$command = new CliApp($argv, $injectables);`

// Optionally, to support PSR-4 namespaces you can set a namespace prefix:
// $cliApp->setPsrNamespacePrefix('FooBar');

// By default, routes appended with 'Route' will be looked for. You can change this to be something else:
// $cliApp->setClassAppend('Command'); // would look for a class called 'XxxxxCommand'

// Run CliApp
$cliApp->run();
```


Examples
--------

```php
<?php
namespace MyApp;

use District5\Cli\CliCommand;

/**
 * Class ExampleOneRoute
 */
class ExampleOneRoute extends CliCommand
{
    public function run()
    {
        $this->outputInfo('Running Example One');
        $this->outputInfo('--------');
        $this->outputInfo('Single line');
        $this->outputInfo(array('This', 'is', 'an', 'array'));
        $this->outputError('Single error line!');
        $this->outputError(array('This', 'is', 'also', 'an', 'array'));
        $this->outputInfo('--------');
        
        if ($this->getArgument(0) !== null) {
            $this->outputInfo('You sent in: ' . $this->getArgument(0));
        }
    }
}
```

Once you've got your example set up, call your main script (see below) and pass one of the commands defined in the PHP
files.

```bash
php ./console.php my-app example-one
```

### Adding some arguments..

There are two different argument types you can pass into this script.
* Simple - Numeric - `./script.php command arg1 arg2 ...`
  * Retrievable with `getArgument(0)` and `getArgument(1)`
* Advanced - Named - `./script.php command --foo=bar --hello=world ...`
  * Arguments are processed by `CliArgvs`. You can retrieve this object in your command by calling `getCliArgvs`
  * Retrievable with `getArgument('foo')` and `getArgument('hello')`
  * You can chain these into an array and calling `getArgument('foo')` would return an array.
    * `./script.php command --foo=bar --foo=another --foo=andAnother`

To send arguments into the route, simply append them. For example, appending `hello` onto the end of the above script
would allow you to use the `getArgument(0)` method within the command to retrieve the parameter.

* In the route:
    ```php
    if ($this->getArgument(0) !== null) {
        $this->outputInfo('You passed in: ' . $this->getArgument(0));
    }
    ```

* Bash:
    ```bash
    php ./console.php my-app example-one hello
    ```

Here's a sample `console.php` that you could use to get started.

```php
<?php
use District5\Cli\CliApp;

$injectables = [
    'config' => [
        'db.name' => 'MyDatabaseName',
        'db.user' => 'root',
        'db.password' => 'password',
    ]
];

$cliApp = CliApp::createApp($argv, $injectables);
$cliApp->run();
```
