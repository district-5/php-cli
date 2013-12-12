<?php
/**
 * OhConsole - a simple console command line tool
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       1.0.0
 * @package     OhConsole
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace OhConsole\Tests;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testExampleOne()
    {
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

        $argv = array(0 => '', 1 => 'ohconsole-example-one');
        // Start OhConsole
        $command = new \OhConsole\OhConsole($argv, $classes, $injectables);

        // Run OhConsole
        @ob_start();
        $command->run();
        $result = @ob_get_clean();
        $this->assertContains('Single error line!', $result);
    }

    public function testExampleTwo()
    {
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

        $argv = array(0 => '', 1 => 'ohconsole-example-two');
        // Start OhConsole
        $command = new \OhConsole\OhConsole($argv, $classes, $injectables);

        // Run OhConsole
        @ob_start();
        $command->run();
        $result = @ob_get_clean();
        $this->assertContains('Single error line!', $result);
    }

    public function testInvalidExample()
    {
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

        $argv = array(0 => '', 1 => 'ohconsole-example-none');
        // Start OhConsole
        $command = new \OhConsole\OhConsole($argv, $classes, $injectables);

        try {
            $command->run();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\OhConsole\Exception\InvalidConsoleArgumentException', $e);
            return;
        }

        $this->fail('expected exception');
    }

    public function testNoClassesProvided()
    {
        $argv = array(0 => '', 1 => 'ohconsole-example-none');
        // Start OhConsole
        $command = new \OhConsole\OhConsole($argv, array());

        try {
            $command->run();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\OhConsole\Exception\NoCommandClassesGivenException', $e);
            return;
        }

        $this->fail('expected exception');
    }

    public function testNoArgvKeyOneSet()
    {
        $classes = array(
            '\OhConsole\Examples\ExampleOne',
            '\OhConsole\Examples\ExampleTwo'
        );
        $argv = array();
        // Start OhConsole
        $command = new \OhConsole\OhConsole($argv, $classes);

        try {
            $command->run();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\OhConsole\Exception\ArgumentNotSetException', $e);
            return;
        }

        $this->fail('expected exception');
    }

    public function testCommnandForTests()
    {
        $classes = array(
            '\OhConsole\Tests\CommandForTests'
        );
        $argv = array(0 => '', 1 => 'ohconsole-command-for-tests');
        $inj = array('name' => 'joe');
        // Start OhConsole
        $command = new \OhConsole\OhConsole($argv, $classes, $inj);

        $command->run();
        $result = @ob_get_clean();
        $this->assertContains('Arguments: 2', $result);
        $this->assertContains('Argument 1: ohconsole-command-for-tests', $result);
        $this->assertContains('Argument invalid: 0', $result);
        $this->assertContains('Injectables: 1', $result);
        $this->assertContains('Injectable name: joe', $result);
        $this->assertContains('Injectable invalid: 0', $result);
        $this->assertContains('ohconsole-command-for-tests', $result);
    }
}
