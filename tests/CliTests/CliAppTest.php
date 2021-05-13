<?php
namespace District5\CliTests;

use District5\Cli\CliApp;
use District5\Cli\Exception\ArgumentNotSetException;
use District5\Cli\Exception\InvalidConsoleArgumentException;
use PHPUnit\Framework\TestCase;

class CliAppTest extends TestCase
{
    public function testInvalidRouter()
    {
        $this->expectException(ArgumentNotSetException::class);
        $command = new CliApp(['foo.php'], []);
        $command->run();
    }

    public function testStaticInvalidRouter()
    {
        $this->expectException(InvalidConsoleArgumentException::class);
        $instance = CliApp::createApp(['foo.php', 'hello'], []);
        $instance->run();
    }

    public function testStaticValidRouter()
    {
        $originalArguments = ['foo.php', 'cli-examples', 'two'];
        $instance = CliApp::createApp($originalArguments, []);
        $instance->run();

        $otherInstance = CliApp::createApp();
        $this->assertEquals($originalArguments, $otherInstance->getCliArguments());
    }

    public function testPrefixedNamespaceValidRouter()
    {
        $originalArguments = ['foo.php', 'prefixed-example'];//, 'two'];
        $instance = CliApp::createApp(
            $originalArguments,
            []
        )->setPsrNamespacePrefix(
            'FooBar'
        );
        $command = $instance->run();
        $this->assertEquals('The prefixed namespace example works.', $command->getResult());
    }

    public function testPrefixedNamespaceWithDifferentAppendOnClassValidRouter()
    {
        $originalArguments = ['foo.php', 'prefixed-example'];//, 'two'];
        $instance = CliApp::createApp(
            $originalArguments,
            []
        )->setPsrNamespacePrefix(
            'FooBar'
        )->setRouteAppend(
            'Joe'
        );
        $command = $instance->run();
        $this->assertEquals('This is Joe!', $command->getResult());
    }

    public function testStaticValidRouterWithExtraArguments()
    {
        $originalArguments = ['foo.php', 'cli-examples', 'two', 'hello', 'world'];
        $instance = CliApp::createApp($originalArguments, []);
        $command = $instance->run();

        $this->assertEquals(['hello', 'world'], $command->getArguments());

        $otherInstance = CliApp::createApp();
        $this->assertEquals($originalArguments, $otherInstance->getCliArguments());
    }

    public function testStaticValidRouterWithExtraArgumentsAndCustomAppend()
    {
        $originalArguments = ['foo.php', 'cli-examples', 'two', 'hello', 'world'];
        $instance = CliApp::createApp($originalArguments, []);
        $instance->setRouteAppend('Foo');
        $command = $instance->run();

        $this->assertEquals(['hello', 'world'], $command->getArguments());

        $otherInstance = CliApp::createApp();
        $this->assertEquals($originalArguments, $otherInstance->getCliArguments());
    }

    public function testOverwriteStaticValidRouterWithExtraArguments()
    {
        $originalArguments = ['foo.php', 'cli-examples', 'two', 'hello', 'world'];
        $instance = CliApp::createApp($originalArguments, []);
        $command = $instance->run();
        $this->assertEquals(['hello', 'world'], $command->getArguments());
        $otherInstance = CliApp::createApp();
        $this->assertEquals($originalArguments, $otherInstance->getCliArguments());

        $originalArguments = ['foo.php', 'cli-examples', 'two', 'foo', 'bar'];
        $instance = CliApp::createApp($originalArguments, []);
        $command = $instance->run();
        $this->assertEquals(['foo', 'bar'], $command->getArguments());
        $otherInstance = CliApp::createApp();
        $this->assertEquals($originalArguments, $otherInstance->getCliArguments());
    }
}
