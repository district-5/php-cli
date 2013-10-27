<?php
/**
 * OhConsole - a simple console command line tool
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     1.0
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
namespace OhConsole;

use OhConsole\Exception\ArgumentNotSetException;
use OhConsole\Exception\InvalidConsoleArgumentException;
use OhConsole\Exception\NoCommandClassesGivenException;

class OhConsole
{
    /**
     * @var array
     */
    private $argv = array();

    /**
     * @var array
     */
    private $classes = array();

    /**
     * @var array
     */
    private $injectables = array();

    /**
     * Construct giving the argv variable
     * @param array $argv
     * @param array $classes
     * @param array $injectables (optional) default empty array
     */
    public function __construct(array $argv, array $classes, array $injectables = array())
    {
        $this->argv = $argv;
        $this->classes = $classes;
        $this->injectables = $injectables;
    }

    /**
     * Run the console command.
     * @throws ArgumentNotSetException
     */
    public function run()
    {
        if (empty($this->classes)) {
            throw new NoCommandClassesGivenException('Array of classes has not been passed.');
        }

        if (!array_key_exists(1, $this->argv)) {
            throw new ArgumentNotSetException('Argument not passed.');
        }

        $found = false;
        foreach ($this->classes as $class) {
            $instance = new $class();
            if ($instance instanceof OhCommand) {
                $command = $instance->getCommand();
                if ($command == $this->argv[1]) {
                    $found = true;
                    $instance->setArguments($this->argv);
                    $instance->setInjectables($this->injectables);
                    $instance->run();
                }
            }
        }

        if (!$found) {
            throw new InvalidConsoleArgumentException();
        }
    }
}
