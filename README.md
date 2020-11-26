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
        "district-5/cli": ">=3.0.1"
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
$injectables = array(
    'config' => array(
        // Put any configuration here.
    )
);

// Start CliApp
$command = new CliApp($argv, $injectables);

// Run CliApp
$command->run();
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
    }
}
```

Once you've got your example set up, call your main script (see below) and pass one of the commands defined in the PHP
files.

```bash
php ./console.php my-app example-one
```

Here's a sample `console.php` that you can use to get started.

```php
<?php
use District5\Cli\CliApp;

$injectables = array(
    'config' => array(
        'db.name' => 'MyDatabaseName',
        'db.user' => 'root',
        'db.password' => 'password',
    )
);

$command = new CliApp($argv, $injectables);
$command->run();
```
