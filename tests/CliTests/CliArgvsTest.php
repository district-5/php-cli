<?php
namespace District5\CliTests;

use District5\Cli\CliArgvs;
use PHPUnit\Framework\TestCase;

class CliArgvsTest extends TestCase
{
    public function testNormal()
    {
        $args = [
            'foo.php',
            '--foo=bar',
            '--name=Joe',
            '--age=23'
        ];
        $inst = CliArgvs::getInstance($args, count($args));
        $arguments = $inst->getArgs();
        $this->assertEquals('foo.php', $inst->getScript());
        $this->assertCount(3, $arguments);
        $this->assertEquals('bar', $inst->getArg('--foo'));
        $this->assertEquals('Joe', $inst->getArg('--name'));
        $this->assertEquals('23', $inst->getArg('--age'));
    }

    public function testNormalRemoveLeadingDashes()
    {
        $args = [
            'foo.php',
            '--foo=bar',
            '--name=Joe',
            '--age=23'
        ];
        $inst = CliArgvs::getInstance($args, count($args), true);
        $arguments = $inst->getArgs();
        $this->assertEquals('foo.php', $inst->getScript());
        $this->assertCount(3, $arguments);
        $this->assertEquals('bar', $inst->getArg('foo'));
        $this->assertEquals('Joe', $inst->getArg('name'));
        $this->assertEquals('23', $inst->getArg('age'));

        $this->assertEquals('bar', $inst->getArg('--foo'));
        $this->assertEquals('Joe', $inst->getArg('--name'));
        $this->assertEquals('23', $inst->getArg('--age'));
    }

    public function testDuplicateKeys()
    {
        $args = [
            'foo.php',
            '--foo=bar',
            '--name=Joe',
            '--name=Jane'
        ];
        $inst = CliArgvs::getInstance($args, count($args));
        $arguments = $inst->getArgs();

        $this->assertEquals('foo.php', $inst->getScript());

        $this->assertCount(3, $arguments);

        $this->assertCount(2, $inst->getArg('--name')); // Joe, Jane

        $this->assertEquals('bar', $inst->getArg('--foo'));
        $this->assertContains('Joe', $inst->getArg('--name'));
        $this->assertContains('Jane', $inst->getArg('--name'));
    }

    public function testDuplicateValues()
    {
        $args = [
            'foo.php',
            '--foo=bar',
            '--name=Joe',
            '--name=Joe'
        ];
        $inst = CliArgvs::getInstance($args, count($args));
        $arguments = $inst->getArgs();

        $this->assertEquals('foo.php', $inst->getScript());

        $this->assertCount(3, $arguments);

        $this->assertEquals('bar', $inst->getArg('--foo'));
        $this->assertEquals('Joe', $inst->getArg('--name', true));
    }

    public function testHelpFunction()
    {
        $args = [
            'foo.php',
            '--help',
            '--name=Joe'
        ];
        $inst = CliArgvs::getInstance($args, count($args));
        $arguments = $inst->getArgs();

        $this->assertEquals('foo.php', $inst->getScript());
        $this->assertCount(1, $arguments);
        $this->assertEquals('Joe', $inst->getArg('--name'));
        $this->assertTrue($inst->hasHelp());

        $argsTwo = [
            'foo.php',
            '--name=Joe',
            '--help'
        ];
        $instTwo = CliArgvs::getInstance($argsTwo, count($argsTwo));
        $arguments = $instTwo->getArgs();

        $this->assertEquals('foo.php', $instTwo->getScript());
        $this->assertCount(1, $arguments);
        $this->assertEquals('Joe', $instTwo->getArg('--name'));
        $this->assertTrue($instTwo->hasHelp());
    }
}
