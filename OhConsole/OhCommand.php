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
namespace OhConsole;

abstract class OhCommand
{
    /**
     * @var array
     */
    protected $argv = array();

    /**
     * @var array
     */
    protected $injectables = array();

    /**
     * @var array
     */
    protected $command = null;

    /**
     * Get the command for this class.
     * @return string|null
     */
    final public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set the argv array
     * @param array $argv
     */
    final public function setArguments(array $argv)
    {
        $this->argv = $argv;
    }

    /**
     * Get the argv array
     * @return array
     */
    final public function getArguments()
    {
        return $this->argv;
    }

    /**
     * Get an argv value, based on key
     * @param integer|string $key
     * @return string|null
     */
    final public function getArgument($key)
    {
        if (array_key_exists($key, $this->getArguments())) {
            return $this->argv[$key];
        }

        return null;
    }

    /**
     * Set the injectables array
     * @param array $argv
     */
    final public function setInjectables(array $injectables)
    {
        $this->injectables = $injectables;
    }

    /**
     * Get the injectables array
     * @return array
     */
    final public function getInjectables()
    {
        return $this->injectables;
    }

    /**
     * Get an injectable based on key name
     * @param integer|string $key
     * @return mixed|null
     */
    final public function getInjectable($key)
    {
        if (array_key_exists($key, $this->getInjectables())) {
            return $this->injectables[$key];
        }

        return null;
    }

    /**
     * Output information to the terminal, via a string, or optionally an array
     * @param string|array $content
     * @param boolean $newLine (optional) default true
     */
    final public function outputInfo($content, $newLine = true)
    {
        if (is_array($content)) {
            foreach ($content as $string) {
                echo $string;
                if ($newLine) {
                    echo PHP_EOL;
                }
            }
        } else {
            echo $content;
            if ($newLine) {
                echo PHP_EOL;
            }
        }
    }

    /**
     * Output errors to the terminal, via a string, or optionally an array
     * @param string|array $content
     * @param boolean $newLine (optional) default true
     */
    final public function outputError($content, $newLine = true)
    {
        $tpl = "\033[0;31m%s\033[0m";
        if (is_array($content)) {
            foreach ($content as $string) {
                echo sprintf($tpl, $string);
                if ($newLine) {
                    echo PHP_EOL;
                }
            }
        } else {
            echo sprintf($tpl, $content);
            if ($newLine) {
                echo PHP_EOL;
            }
        }
    }

    abstract function run();
}
