<?php
namespace District5\CliTests;

use District5\Cli\CliRouter;
use PHPUnit\Framework\TestCase;

class CliRouterTest extends TestCase
{
    public function testInvalidRouter()
    {
        $command = 'foo.php';
        $arguments = explode(' ', $command);
        $instance = CliRouter::getClassForRoute($arguments, []);
        $this->assertNull($instance);

        $commandTwo = 'foo.php bar hello';
        $argumentsTwo = explode(' ', $commandTwo);
        $instanceTwo = CliRouter::getClassForRoute($argumentsTwo, []);
        $this->assertNull($instanceTwo);
    }

    public function testRouterOneBasic()
    {
        $command = 'foo.php cli-examples route-one';
        $arguments = explode(' ', $command);
        $instance = CliRouter::getClassForRoute($arguments, []);
        $this->assertNotNull($instance);
        $this->assertEquals('RouteOneRoute', $instance->run());
        $this->assertCount(0, $instance->getArguments());
    }

    public function testRouterTwoBasic()
    {
        $command = 'foo.php cli-examples two';
        $arguments = explode(' ', $command);
        $instance = CliRouter::getClassForRoute($arguments, []);
        $this->assertNotNull($instance);
        $this->assertEquals('TwoRoute', $instance->run());
        $this->assertCount(0, $instance->getArguments());
    }

    public function testRouterOneWithArguments()
    {
        $command = 'foo.php cli-examples route-one foo bar';
        $arguments = explode(' ', $command);
        $instance = CliRouter::getClassForRoute($arguments, []);
        $this->assertNotNull($instance);
        $this->assertNotEmpty($instance->getArguments());
        $this->assertCount(2, $instance->getArguments());
        $this->assertEquals('foo', $instance->getArguments()[0]);
        $this->assertEquals('bar', $instance->getArguments()[1]);
        $this->assertEquals('foo', $instance->getArgument(0));
        $this->assertEquals('bar', $instance->getArgument(1));
    }

    public function testRouterTwoWithArguments()
    {
        $command = 'foo.php cli-examples two hello world --foo=bar --joe=bloggs';
        $arguments = explode(' ', $command);
        $instance = CliRouter::getClassForRoute($arguments, []);
        $this->assertNotNull($instance);
        $this->assertNotEmpty($instance->getArguments());
        $this->assertCount(4, $instance->getArguments());
        $this->assertEquals('hello', $instance->getArguments()[0]);
        $this->assertEquals('world', $instance->getArguments()[1]);
        $this->assertEquals('--foo=bar', $instance->getArguments()[2]);
        $this->assertEquals('--joe=bloggs', $instance->getArguments()[3]);
        $this->assertEquals('hello', $instance->getArgument(0));
        $this->assertEquals('world', $instance->getArgument(1));
        $this->assertEquals('--foo=bar', $instance->getArgument(2));
        $this->assertEquals('bar', $instance->getArgument('foo'));
        $this->assertEquals('--joe=bloggs', $instance->getArgument(3));
        $this->assertEquals('bloggs', $instance->getArgument('joe'));
    }
}
